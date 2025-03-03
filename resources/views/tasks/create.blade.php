@extends('adminlte::page')

@section('title', 'Dodaj zadanie')

@section('content_header')
    <h1>Dodaj zadanie</h1>
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nazwa</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}">
            @error('title')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label>Priorytet</label>
            <select name="priority" class="form-control">
                <option value="medium">Średni</option>
                <option value="high">Wysoki</option>
            </select>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="to-do">Do zrobienia</option>
                <option value="in-progress">W trakcie</option>
                <option value="done">Zrobione</option>
            </select>
        </div>
        <div class="form-group">
            <label>Data wykonania</label>
            <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}" style="width: 200px;">
            @error('due_date')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-success">Zapisz</button>
    </form>
@stop
