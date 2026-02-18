@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">工数記録 編集</h1>
    <form action="{{ route('time-entries.update', $timeEntry) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block">タスクID</label>
            <input type="number" name="task_id" class="border rounded w-full" value="{{ old('task_id', $timeEntry->task_id) }}" required>
        </div>
        <div class="mb-4">
            <label class="block">作業時間 (h)</label>
            <input type="number" step="0.01" name="hours" class="border rounded w-full" value="{{ old('hours', $timeEntry->hours) }}" required>
        </div>
        <div class="mb-4">
            <label class="block">作業日</label>
            <input type="date" name="work_date" class="border rounded w-full" value="{{ old('work_date', $timeEntry->work_date) }}" required>
        </div>
        <div class="mb-4">
            <label class="block">内容</label>
            <textarea name="description" class="border rounded w-full">{{ old('description', $timeEntry->description) }}</textarea>
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">更新</button>
    </form>
</div>
@endsection
