<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $tasks = Task::query()
            ->where('user_id', auth()->id())
            ->get();

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
}
