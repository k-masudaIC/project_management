@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">日次工数入力</h1>
    <form action="{{ route('time-entries.daily.store') }}" method="POST">
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
            <label class="block">日付</label>
            <input type="date" name="work_date" class="border rounded w-full" value="{{ old('work_date', $work_date ?? date('Y-m-d')) }}" required onchange="window.location='{{ route('time-entries.daily') }}?work_date='+this.value;">
        </div>
        <div class="mb-4">
            <label class="block">タスクごとの工数</label>
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="px-4 py-2">タスクID</th>
                        <th class="px-4 py-2">作業時間 (h)</th>
                        <th class="px-4 py-2">内容</th>
                        <th class="px-4 py-2">操作</th>
                    </tr>
                </thead>
                <tbody id="task-rows">
                    @php $oldTasks = old('tasks', isset($entries) ? $entries->toArray() : []); @endphp
                    @forelse($oldTasks as $i => $task)
                        <tr>
                            <td>
                                <select name="tasks[{{ $i }}][task_id]" class="border rounded w-full" required>
                                    <option value="">タスクを選択</option>
                                    @foreach($tasks as $taskOption)
                                        @foreach($taskOption->assignments as $assign)
                                            <option value="{{ $taskOption->id }}" data-user="{{ $assign->user->name ?? '' }}" data-project="{{ $taskOption->project->name ?? '' }}" @if(isset($task['task_id']) && $task['task_id'] == $taskOption->id) selected @endif>
                                                {{ $taskOption->title }}（{{ $taskOption->project->name ?? '' }}）- {{ $assign->user->name ?? '' }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="0.01" name="tasks[{{ $i }}][hours]" class="border rounded w-full" required placeholder="例：1.5" value="{{ $task['hours'] ?? '' }}"></td>
                            <td><input type="text" name="tasks[{{ $i }}][description]" class="border rounded w-full" placeholder="作業内容" value="{{ $task['description'] ?? '' }}"></td>
                            <td><button type="button" class="remove-task bg-red-100 text-red-600 border border-red-400 rounded px-2 py-1">削除</button></td>
                        </tr>
                    @empty
                        <tr>
                            <td>
                                <select name="tasks[0][task_id]" class="border rounded w-full" required>
                                    <option value="">タスクを選択</option>
                                    @foreach($tasks as $taskOption)
                                        @foreach($taskOption->assignments as $assign)
                                            <option value="{{ $taskOption->id }}" data-user="{{ $assign->user->name ?? '' }}" data-project="{{ $taskOption->project->name ?? '' }}">
                                                {{ $taskOption->title }}（{{ $taskOption->project->name ?? '' }}）- {{ $assign->user->name ?? '' }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="0.01" name="tasks[0][hours]" class="border rounded w-full" required placeholder="例：1.5"></td>
                            <td><input type="text" name="tasks[0][description]" class="border rounded w-full" placeholder="作業内容"></td>
                            <td><button type="button" class="remove-task bg-red-400 text-white rounded px-2 py-1">削除</button></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <button type="button" id="add-task" class="bg-gray-300 px-2 py-1 rounded mt-2">行を追加</button>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">登録</button>
    </form>
</div>
<script>
const rows = document.getElementById('task-rows');
const tasksData = @json($tasks);
document.getElementById('add-task').onclick = function() {
    const idx = rows.children.length;
    const selectHtml = (() => {
        let html = '<select name="tasks['+idx+'][task_id]" class="border rounded w-full" required><option value="">タスクを選択</option>';
        tasksData.forEach(task => {
            if(task.assignments && task.assignments.length > 0) {
                task.assignments.forEach(assign => {
                    html += `<option value="${task.id}" data-user="${assign.user?.name ?? ''}" data-project="${task.project?.name ?? ''}">${task.title}（${task.project?.name ?? ''}）- ${assign.user?.name ?? ''}</option>`;
                });
            }
        });
        html += '</select>';
        return html;
    })();
    const tr = document.createElement('tr');
    tr.innerHTML = `<td>${selectHtml}</td><td><input type="number" step="0.01" name="tasks[${idx}][hours]" class="border rounded w-full" required placeholder="例：1.5"></td><td><input type="text" name="tasks[${idx}][description]" class="border rounded w-full" placeholder="作業内容"></td><td><button type="button" class="remove-task bg-red-100 text-red-600 border border-red-400 rounded px-2 py-1">削除</button></td>`;
    rows.appendChild(tr);
    attachRemoveHandlers();
};
function attachRemoveHandlers() {
    document.querySelectorAll('.remove-task').forEach(btn => {
        btn.onclick = function() {
            if (rows.children.length > 1) {
                this.closest('tr').remove();
            }
        };
    });
}
attachRemoveHandlers();
</script>
@endsection
