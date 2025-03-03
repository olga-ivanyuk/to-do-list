<?php

namespace App\Jobs;

use App\Mail\TaskReminderMail;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendTaskReminderJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Task $task)
    {}

    /**
     * @return void
     */
    public function handle(): void
    {
        if ($this->task->user) {
            Mail::to($this->task->user->email)->send(new TaskReminderMail($this->task));
        }
    }
}
