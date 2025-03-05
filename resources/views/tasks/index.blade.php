@extends('adminlte::page')

@section('title', 'Lista zadań')

@section('content_header')
    <h1>Lista zadań</h1>
@stop

@section('content')
    <a href="{{ route('tasks.create') }}" class="btn btn-success mb-3">Dodaj zadanie</a>
    <form method="GET" action="{{ route('tasks.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">Wszystkie</option>
                    <option value="to-do" {{ request('status') == 'to-do' ? 'selected' : '' }}>Do zrobienia</option>
                    <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>W trakcie
                    </option>
                    <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Zakończone</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="priority">Priorytet</label>
                <select name="priority" id="priority" class="form-control">
                    <option value="">Wszystkie</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Niski</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Średni</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Wysoki</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="due_date">Termin wykonania</label>
                <input type="date" name="due_date" id="due_date" value="{{ request('due_date') }}" class="form-control">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filtruj</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Wyczyść</a>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nazwa</th>
            <th>Priorytet</th>
            <th>Status</th>
            <th>Data wykonania</th>
            <th style="width: 30%; white-space: nowrap;">Akcje</th>
            <th>Link</th>
        </tr>
        </thead>
        <tbody>
        @forelse($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>
            <span class="badge
                {{ $task->priority == 'high' ? 'bg-danger' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-success') }}">
                {{ ucfirst($task->priority) }}
            </span>
                </td>
                <td>
            <span class="badge
                {{ $task->status == 'done' ? 'bg-success' : ($task->status == 'in-progress' ? 'bg-primary' : 'bg-secondary') }}">
                {{ ucfirst($task->status) }}
            </span>
                </td>
                <td>{{ $task->due_date->format('d.m.Y') }}</td>
                <td>
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-warning">Edytuj</a>
                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-info">Zobacz</a>

                    <form action="{{ route('tasks.generate_link', $task) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">Utwórz publiczny link</button>
                    </form>

                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Usunąć zadanie?')">
                            Usuń
                        </button>
                    </form>
                </td>
                @if($task->access_token)
                    <td>
                        <input type="text" id="sharedLink" class="form-control"
                               value="{{ route('tasks.shared', $task->access_token) }}" readonly>
                        <button class="btn btn-secondary mt-2" onclick="copyLink()">Skopiuj</button>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Brak zadań</td>
            </tr>
        @endforelse

        </tbody>
    </table>
@stop
<script>
    function copyLink() {
        let copyText = document.getElementById("sharedLink");
        console.log(copyText);
        navigator.clipboard.writeText(copyText.value)
            .then(() => {
                alert("Link skopiowany!");
            })
            .catch(err => {
                console.error("Błąd podczas kopiowania linku: ", err);
            });
    }
</script>
