@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">工数記録 新規登録</h1>
    <form action="{{ route('time-entries.store') }}" method="POST">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @csrf
        <div class="mb-4">
            <label class="block">タスクID</label>
            <input type="number" name="task_id" class="border rounded w-full" value="{{ old('task_id') }}" required placeholder="例：101">
        </div>
        <div class="mb-4">
            <label class="block">作業時間 (h)</label>
            <input type="number" step="0.01" name="hours" class="border rounded w-full" value="{{ old('hours') }}" required placeholder="例：1.5">
        </div>
        <div class="mb-4">
            <label class="block">作業日</label>
            <input type="date" name="work_date" class="border rounded w-full" value="{{ old('work_date') }}" required>
        </div>
        <div class="mb-4">
            <label class="block">内容</label>
            <textarea name="description" class="border rounded w-full" placeholder="作業内容">{{ old('description') }}</textarea>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">登録</button>
    </form>
</div>
@endsection
