<!DOCTYPE html>
<html>
<head>
    <title>Przypomnienie o zadaniu</title>
</head>
<body>
<h2>Cześć, {{ $task->user->name }}!</h2>
<p>Przypominamy, że termin wykonania zadania <strong>{{ $task->title }}</strong> upływa {{ $task->due_date->format('d.m.Y') }}.</p>
<p>Nie zapomnij go wykonać!</p>
<p><a href="{{ route('tasks.show', $task) }}">Zobacz szczegóły</a></p>
</body>
</html>

