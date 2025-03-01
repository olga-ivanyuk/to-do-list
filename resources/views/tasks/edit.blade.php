@extends('adminlte::page')

@section('title', 'Edytuj zadanie')

@section('content_header')
    <h1>Edytuj zadanie</h1>
@stop

@section('content')
    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Tytuł</label>
            <input type="text" name="title" class="form-control" value="{{ $task->title }}" required>
        </div>
        <div class="form-group">
            <label>Priorytet</label>
            <select name="priority" class="form-control">
                <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Średni</option>
                <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>Wysoki</option>
            </select>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="to-do" {{ $task->status == 'to-do' ? 'selected' : '' }}>Do zrobienia</option>
                <option value="in-progress" {{ $task->status == 'in-progress' ? 'selected' : '' }}>W trakcie</option>
                <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Zrobione</option>
            </select>
        </div>
        <div class="form-group">
            <label>Data wykonania</label>
            <input type="date" name="due_date" class="form-control" style="width: 200px;" value="{{ $task->due_date->format('Y-m-d') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Aktualizuj</button>
    </form>
@stop
