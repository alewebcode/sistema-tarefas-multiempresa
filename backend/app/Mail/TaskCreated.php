<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskCreated extends Mailable implements ShouldQueue 
{
    use Queueable, SerializesModels;

    public $task;
    public $user;

    public function __construct(Task $task, User $user)
    {
        $this->task = $task;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Nova tarefa criada: ' . $this->task->title)
                    ->view('emails.task-created')
                    ->with([
                        'taskTitle' => $this->task->title,
                        'taskDescription' => $this->task->description,
                        'taskPriority' => $this->task->priority,
                        'taskDueDate' => $this->task->due_date,
                        'userName' => $this->user->name,
                        'companyName' => $this->user->company->name
                    ]);
    }
}