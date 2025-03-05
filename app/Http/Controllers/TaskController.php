<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;
use Carbon\Carbon;
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
        $query = Task::query()->where('user_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        $tasks = $query->get();

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
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        $task = Task::query()
            ->create($validated);

        $this->attachToGoogleCalendar($task);

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
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Brak dostępu do tego zadania');
        }

        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $task->update($validated);

        if ($task->google_calendar_event_id) {
            $event = Event::find($task->google_calendar_event_id);
            if ($event) {
                $event->name = $task->title;
                $event->startDateTime = Carbon::parse($task->due_date);
                $event->endDateTime = Carbon::parse($task->due_date);
                $event->save();
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Zadanie zaktualizowane i zapisane w Google Calendar!');
    }

    /**
     * @param Task $task
     * @return RedirectResponse
     */
    public function destroy(Task $task): RedirectResponse
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Brak dostępu do tego zadania');
        }

        if (!empty($task->google_calendar_event_id)) {
            $event = Event::find($task->google_calendar_event_id);
            if ($event) {
                $event->delete();
            }
        }

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
    public function showSharedTask($token)
    {
        $task = Task::query()
            ->where('access_token', $token)
            ->where('access_expires_at', '>', now())
            ->first();

        if (!$task) {
            abort(404, 'Link jest nieprawidłowy lub wygasł.');
        }

        return view('tasks.shared', compact('task'));
    }

    /**
     * @param Task $task
     * @return RedirectResponse
     */
    public function attachToGoogleCalendar(Task $task): RedirectResponse
    {
        $event = new Event;
        $event->name = $task->title;
        $event->startDate = Carbon::parse($task->due_date);
        $event->endDate = Carbon::parse($task->due_date);
        $event->setColorId(6);
        $event = $event->save();

        $task->google_calendar_event_id = $event->id;
        $task->save();

        return back()->with('success', 'Zadanie dodano do Google Calendar');
    }
}
