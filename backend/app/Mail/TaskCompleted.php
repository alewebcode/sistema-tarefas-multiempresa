<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskCompleted extends Mailable implements ShouldQueue 
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
        return $this->subject('Tarefa concluída: ' . $this->task->title)
                    ->view('emails.task-completed')
                    ->with([
                        'taskTitle' => $this->task->title,
                        'taskDescription' => $this->task->description,
                        'userName' => $this->user->name,
                        'companyName' => $this->user->company->name
                    ]);
    }
}