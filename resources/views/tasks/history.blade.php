@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Historia zmian zadania: {{ $task->title }}</h1>

        <!-- Jeśli historia jest pusta -->
        @if($history->isEmpty())
            <div class="alert alert-info">
                Historia zmian jest pusta.
            </div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Pole</th>
                    <th>Stara wartość</th>
                    <th>Nowa wartość</th>
                    <th>Data zmiany</th>
                    <th>Użytkownik</th>
                </tr>
                </thead>
                <tbody>
                @foreach($history as $entry)
                    <tr>
                        <td>{{ ucfirst($entry->field) }}</td>
                        <td>{{ $entry->old_value }}</td>
                        <td>{{ $entry->new_value }}</td>
                        <td>{{ $entry->created_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $entry->user->name }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        <a href="{{ route('tasks.index') }}" class="btn btn-primary mt-3">Wróć do zadań</a>
    </div>
@endsection

