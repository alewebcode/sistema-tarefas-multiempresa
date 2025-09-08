<!-- resources/views/emails/task-completed.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Tarefa Concluída</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #28a745; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .task-info { background: white; padding: 15px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tarefa Concluída!</h1>
        </div>
        <div class="content">
            <p>Parabéns {{ $userName }}!</p>
            <p>A seguinte tarefa foi marcada como concluída na empresa {{ $companyName }}:</p>
            
            <div class="task-info">
                <h3>{{ $taskTitle }}</h3>
                @if($taskDescription)
                    <p><strong>Descrição:</strong> {{ $taskDescription }}</p>
                @endif
            </div>
            
            <p>Continue assim! Acesse o sistema para ver suas próximas tarefas.</p>
        </div>
    </div>
</body>
</html>