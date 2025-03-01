@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Szczegóły zadania</h2>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $task->title }}</h5>
                <p class="card-text"><strong>Priorytet:</strong> {{ ucfirst($task->priority) }}</p>
                <p class="card-text"><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $task->status)) }}</p>
                <p class="card-text"><strong>Termin wykonania:</strong> {{ $task->due_date->format('d.m.Y') }}</p>
                <p class="card-text"><strong>Autor:</strong> {{ $task->user->name ?? 'Nieznany' }}</p>

                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Wróć</a>
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary">Edytuj</a>
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Czy na pewno chcesz usunąć?')">Usuń</button>
                </form>
            </div>
        </div>
    </div>
@endsection
