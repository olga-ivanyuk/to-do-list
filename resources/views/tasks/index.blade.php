@extends('adminlte::page')

@section('title', 'Lista zadań')

@section('content_header')
    <h1>Lista zadań</h1>
@stop

@section('content')
    <a href="{{ route('tasks.create') }}" class="btn btn-success mb-3">Dodaj zadanie</a>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nazwa</th>
            <th>Priorytet</th>
            <th>Status</th>
            <th>Data wykonania</th>
            <th>Akcje</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ ucfirst($task->priority) }}</td>
                <td>{{ ucfirst($task->status) }}</td>
                <td>{{ $task->due_date }}</td>
                <td>
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary btn-sm">Edytuj</a>
                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-info btn-sm">Zobacz</a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Czy na pewno chcesz usunąć?')">Usuń</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
