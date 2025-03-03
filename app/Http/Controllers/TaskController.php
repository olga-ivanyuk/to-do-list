<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

        Task::query()
            ->create($validated);

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
        $validated = $request->validated();

        $validated['user_id'] = auth()->id();

        $task->update($validated);

        return redirect()->route('tasks.index');
    }

    /**
     * @param Task $task
     * @return RedirectResponse
     */
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();
        return redirect()->route('tasks.index');
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
}
