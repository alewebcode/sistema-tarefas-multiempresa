<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Mail\TaskCreated;
use App\Mail\TaskCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function __construct()
    {   
       
        $this->middleware('auth:api');
        
    }

    public function index(Request $request)
    {
        $user = auth('api')->user();
        $query = Task::forCompany($user->company_id)->with('user');

  
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

  
        $query->orderBy('created_at', 'desc');

        $tasks = $query->paginate(15);

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:' . implode(',', Task::getStatuses()),
            'priority' => 'nullable|in:' . implode(',', Task::getPriorities()),
            'due_date' => 'nullable|date|after_or_equal:today'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? Task::STATUS_PENDENTE,
            'priority' => $request->priority ?? Task::PRIORITY_MEDIA,
            'due_date' => $request->due_date,
            'user_id' => $user->id,
            'company_id' => $user->company_id
        ]);

        $task->load('user');

        // Enviar email de notificação
        try {
            
            Mail::to($user->email)->queue((new TaskCompleted($task, $user))->delay(now()->addSeconds(30)));
        } catch (\Exception $e) {
   
            \Log::error('Falha ao enviar email de criação de tarefa: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Tarefa criada com sucesso',
            'task' => $task
        ], 201);
    }

    public function show($id)
    {
        $user = auth('api')->user();
        
        $task = Task::forCompany($user->company_id)
            ->with('user')
            ->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tarefa não encontrada'], 404);
        }

        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        
        $user = auth('api')->user();
        
        $task = Task::forCompany($user->company_id)->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tarefa não encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:' . implode(',', Task::getStatuses()),
            'priority' => 'nullable|in:' . implode(',', Task::getPriorities()),
            'due_date' => 'nullable|date|after_or_equal:today'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $oldStatus = $task->status;

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? $task->status,
            'priority' => $request->priority ?? $task->priority,
            'due_date' => $request->due_date
        ]);

        $task->load('user');

        // Enviar email se tarefa foi concluída
        if ($oldStatus !== Task::STATUS_CONCLUIDA && $task->status === Task::STATUS_CONCLUIDA) {
            try {
                Mail::to($user->email)->queue(new TaskCompleted($task, $user));
            } catch (\Exception $e) {
                \Log::error('Falha ao enviar email de conclusão de tarefa: ' . $e->getMessage());
            }
        }

        return response()->json([
            'message' => 'Tarefa atualizada com sucesso',
            'task' => $task
        ]);
    }

    public function destroy($id)
    {
        $user = auth('api')->user();
        
        $task = Task::forCompany($user->company_id)->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tarefa não encontrada'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Tarefa removida com sucesso']);
    }
}