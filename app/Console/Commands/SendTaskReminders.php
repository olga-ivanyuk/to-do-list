<?php

namespace App\Console\Commands;

use App\Jobs\SendTaskReminderJob;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'Wysyła przypomnienia o zadaniach na dzień przed terminem';

    /**
     * @return void
     */
    public function handle(): void
    {
        $tasks = Task::query()->whereDate('due_date', Carbon::tomorrow())->get();

        foreach ($tasks as $task) {
            dispatch(new SendTaskReminderJob($task));
        }

        $this->info('Przypomnienia zostały wysłane.');
    }
}
