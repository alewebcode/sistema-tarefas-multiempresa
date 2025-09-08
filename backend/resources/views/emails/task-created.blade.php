<!-- resources/views/emails/task-created.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Nova Tarefa Criada</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .task-info { background: white; padding: 15px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nova Tarefa Criada</h1>
        </div>
        <div class="content">
            <p>Olá {{ $userName }},</p>
            <p>Uma nova tarefa foi criada na empresa {{ $companyName }}:</p>
            
            <div class="task-info">
                <h3>{{ $taskTitle }}</h3>
                @if($taskDescription)
                    <p><strong>Descrição:</strong> {{ $taskDescription }}</p>
                @endif
                <p><strong>Prioridade:</strong> {{ ucfirst($taskPriority) }}</p>
                @if($taskDueDate)
                    <p><strong>Data Limite:</strong> {{ \Carbon\Carbon::parse($taskDueDate)->format('d/m/Y') }}</p>
                @endif
            </div>
            
            <p>Acesse o sistema para gerenciar suas tarefas.</p>
        </div>
    </div>
</body>
</html>

