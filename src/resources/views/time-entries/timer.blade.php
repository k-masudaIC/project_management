@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">タイマー付き工数記録</h1>
    <form id="timer-form" class="mb-4">
        @csrf
        <div class="mb-4">
            <label class="block">タスクID</label>
            <input type="number" name="task_id" id="task_id" class="border rounded w-full" required>
        </div>
        <button type="button" id="start-btn" class="bg-blue-500 text-white px-4 py-2 rounded">タイマー開始</button>
        <button type="button" id="stop-btn" class="bg-red-500 text-white px-4 py-2 rounded ml-2" disabled>タイマー停止</button>
        <div class="mt-4">
            <span id="timer-display" class="text-xl font-mono">00:00:00</span>
        </div>
    </form>
    <div id="result" class="mt-4 text-green-600"></div>
</div>
<script>
let timerId = null;
let startTime = null;
let entryId = null;

function formatTime(sec) {
    const h = String(Math.floor(sec / 3600)).padStart(2, '0');
    const m = String(Math.floor((sec % 3600) / 60)).padStart(2, '0');
    const s = String(sec % 60).padStart(2, '0');
    return `${h}:${m}:${s}`;
}

document.getElementById('start-btn').onclick = async function() {
    const taskId = document.getElementById('task_id').value;
    if (!taskId) return alert('タスクIDを入力してください');
    const res = await fetch("{{ route('timer.start') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
        },
        body: JSON.stringify({ task_id: taskId })
    });
    const data = await res.json();
    if (data.id) {
        entryId = data.id;
        startTime = Date.now();
        timerId = setInterval(() => {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            document.getElementById('timer-display').textContent = formatTime(elapsed);
        }, 1000);
        document.getElementById('start-btn').disabled = true;
        document.getElementById('stop-btn').disabled = false;
    }
};

document.getElementById('stop-btn').onclick = async function() {
    if (!entryId) return;
    const res = await fetch(`{{ url('/timer/stop') }}/${entryId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
        }
    });
    const data = await res.json();
    clearInterval(timerId);
    document.getElementById('timer-display').textContent = '00:00:00';
    document.getElementById('start-btn').disabled = false;
    document.getElementById('stop-btn').disabled = true;
    document.getElementById('result').textContent = `記録: ${data.hours} 時間 登録されました`;
    entryId = null;
};
</script>
@endsection
