@props(['task', 'users'])
<div class="mb-4">
    <h5 class="font-bold mb-2">担当者</h5>
    <ul class="mb-2">
        @foreach($task->assignments as $assignment)
            <li class="flex items-center mb-1">
                <span class="mr-2">{{ $assignment->user->name }}</span>
                @can('delete', $assignment)
                    <form method="POST" action="{{ route('task-assignments.destroy', $assignment) }}" onsubmit="return confirm('担当者アサインを解除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">解除</button>
                    </form>
                @endcan
            </li>
        @endforeach
    </ul>
    @can('assign', App\Models\TaskAssignment::class)
        <form method="POST" action="{{ route('task-assignments.store') }}" class="flex items-center space-x-2">
            @csrf
            <input type="hidden" name="task_id" value="{{ $task->id }}">
            <select name="user_id" class="border rounded px-2 py-1">
                <option value="">担当者を選択</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">アサイン</button>
        </form>
    @endcan
</div>
