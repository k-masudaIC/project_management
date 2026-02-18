@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">工数記録 詳細</h1>
    <div class="mb-4">
        <strong>タスクID:</strong> {{ $timeEntry->task_id }}<br>
        <strong>ユーザー:</strong> {{ $timeEntry->user->name ?? '-' }}<br>
        <strong>作業時間:</strong> {{ $timeEntry->hours }} h<br>
        <strong>作業日:</strong> {{ $timeEntry->work_date }}<br>
        <strong>内容:</strong> {{ $timeEntry->description }}<br>
        <strong>開始:</strong> {{ $timeEntry->started_at }}<br>
        <strong>終了:</strong> {{ $timeEntry->ended_at }}<br>
    </div>
    <a href="{{ route('time-entries.edit', $timeEntry) }}" class="bg-green-500 text-white px-4 py-2 rounded">編集</a>
    <form action="{{ route('time-entries.destroy', $timeEntry) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded ml-2" onclick="return confirm('削除しますか？')">削除</button>
    </form>
    <a href="{{ route('time-entries.index') }}" class="ml-4 text-blue-600">一覧へ戻る</a>
</div>
@endsection
