@extends('adminlte::page')

@section('title', 'Lista zadań')

@section('content')
    <div class="container">
        <h2>{{ $task->title }}</h2>
        <p><strong>Priorytet:</strong> {{ ucfirst($task->priority) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($task->status) }}</p>
        <p><strong>Data wykonania:</strong> {{ $task->due_date->format('d.m.Y') }}</p>
    </div>
@endsection
