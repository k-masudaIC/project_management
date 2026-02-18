@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">日次工数入力</h1>
    <form action="{{ route('time-entries.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block">日付</label>
            <input type="date" name="work_date" class="border rounded w-full" value="{{ old('work_date', date('Y-m-d')) }}" required>
        </div>
        <div class="mb-4">
            <label class="block">タスクごとの工数</label>
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="px-4 py-2">タスクID</th>
                        <th class="px-4 py-2">作業時間 (h)</th>
                        <th class="px-4 py-2">内容</th>
                    </tr>
                </thead>
                <tbody id="task-rows">
                    <tr>
                        <td><input type="number" name="tasks[0][task_id]" class="border rounded w-full" required></td>
                        <td><input type="number" step="0.01" name="tasks[0][hours]" class="border rounded w-full" required></td>
                        <td><input type="text" name="tasks[0][description]" class="border rounded w-full"></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" id="add-task" class="bg-gray-300 px-2 py-1 rounded mt-2">行を追加</button>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">登録</button>
    </form>
</div>
<script>
document.getElementById('add-task').onclick = function() {
    const rows = document.getElementById('task-rows');
    const idx = rows.children.length;
    const tr = document.createElement('tr');
    tr.innerHTML = `<td><input type="number" name="tasks[${idx}][task_id]" class="border rounded w-full" required></td><td><input type="number" step="0.01" name="tasks[${idx}][hours]" class="border rounded w-full" required></td><td><input type="text" name="tasks[${idx}][description]" class="border rounded w-full"></td>`;
    rows.appendChild(tr);
};
</script>
@endsection
