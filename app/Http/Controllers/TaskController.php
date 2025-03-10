<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;
use Carbon\Carbon;
use Google\Service\Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\GoogleCalendar\Event;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showSharedTask']);
    }

    /**
     * @return View
     */
    public function index(Request $request): View
    {
        $tasks = $this->applyFilters(Task::query()->where('user_id', auth()->id()), $request)->get();
        $this->cleanUpCancelledTasks($tasks);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * @param Task $task
     * @return View
     */
    public function show(Task $task): View
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $task = Task::query()->create(array_merge($request->validated(), ['user_id' => auth()->id()]));
        $this->syncWithGoogleCalendar($task);

        return redirect()->route('tasks.index');
    }

    /**
     * @param Task $task
     * @return View
     */
    public function edit(Task $task): View
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * @param UpdateRequest $request
     * @param Task $task
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, Task $task): RedirectResponse
    {
        $this->authorizeTaskOwner($task);
        $task->update(array_merge($request->validated(), ['user_id' => auth()->id()]));
        $this->syncWithGoogleCalendar($task);

        return redirect()->route('tasks.index')->with('success', 'Zadanie zaktualizowane i zapisane w Google Calendar!');
    }

    /**
     * @param Task $task
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorizeTaskOwner($task);
        $this->deleteFromGoogleCalendar($task);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Zadanie usunięto razem z Google Calendar');
    }

    /**
     * @param Task $task
     * @return RedirectResponse
     */
    public function generateLink(Task $task): RedirectResponse
    {
        $task->generateAccessToken();

        return back()->with('success', 'Link został utworzony: ' . route('tasks.shared', $task->access_token));
    }

    /**
     * @param $token
     * @return Factory|\Illuminate\Contracts\View\View|Application|object
     */
    public function showSharedTask($token): View
    {
        $task = Task::query()
            ->where('access_token', $token)
            ->where('access_expires_at', '>', now())
            ->firstOrFail();

        if (!$task) {
            abort(404, 'Link jest nieprawidłowy lub wygasł.');
        }

        return view('tasks.shared', compact('task'));
    }

    /**
     * @param Task $task
     * @return View
     */
    public function showHistory(Task $task): View
    {
        $history = $task->histories()->orderBy('created_at', 'desc')->get();

        return view('tasks.history', compact('task', 'history'));
    }

    /**
     * @param $query
     * @param Request $request
     * @return mixed
     */
    private function applyFilters($query, Request $request): mixed
    {
        return $query
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('priority'), fn($q) => $q->where('priority', $request->priority))
            ->when($request->filled('due_date'), fn($q) => $q->whereDate('due_date', $request->due_date));
    }

    /**
     * @param $tasks
     * @return void
     */
    private function cleanUpCancelledTasks($tasks): void
    {
        foreach ($tasks as $task) {
            if ($task->google_calendar_event_id) {
                try {
                    $event = Event::find($task->google_calendar_event_id);
                    if (!$event || $event->status === 'cancelled') {
                        $task->delete();
                    }
                } catch (\Google\Service\Exception $e) {
                    if ($e->getCode() === 410) {
                        $task->delete();
                    }
                }
            }
        }
    }

    /**
     * @param Task $task
     * @return void
     */
    private function syncWithGoogleCalendar(Task $task): void
    {
        if (!$task->google_calendar_event_id) {
            $event = new Event;
            $event->name = $task->title;
            $event->startDate = Carbon::parse($task->due_date);
            $event->endDate = Carbon::parse($task->due_date);
            $event->setColorId(6);
            $event = $event->save();

            $task->update(['google_calendar_event_id' => $event->id]);
        } else {
            $event = Event::find($task->google_calendar_event_id);
            if ($event) {
                $event->name = $task->title;
                $event->startDateTime = Carbon::parse($task->due_date);
                $event->endDateTime = Carbon::parse($task->due_date);
                $event->save();
            }
        }
    }

    /**
     * @param Task $task
     * @return void
     */
    private function authorizeTaskOwner(Task $task): void
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Brak dostępu do tego zadania');
        }
    }

    /**
     * @param Task $task
     * @return void
     * @throws Exception
     */
    private function deleteFromGoogleCalendar(Task $task): void
    {
        if ($task->google_calendar_event_id) {
            try {
                $event = Event::find($task->google_calendar_event_id);
                if ($event) {
                    $event->delete();
                }
            } catch (\Google\Service\Exception $e) {
                if ($e->getCode() !== 410) {
                    throw $e;
                }
            }
        }
    }
}
