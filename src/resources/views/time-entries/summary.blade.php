@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">工数集計（ユーザー別）</h1>
    <form method="GET" action="" class="mb-4 flex gap-2">
        <input type="text" name="user_id" value="{{ request('user_id') }}" class="border rounded px-2 py-1" placeholder="ユーザーID">
        <input type="text" name="project_id" value="{{ request('project_id') }}" class="border rounded px-2 py-1" placeholder="案件ID">
        <input type="date" name="from" value="{{ request('from') }}" class="border rounded px-2 py-1" placeholder="開始日">
        <input type="date" name="to" value="{{ request('to') }}" class="border rounded px-2 py-1" placeholder="終了日">
        <button type="submit" class="bg-gray-400 text-white px-2 py-1 rounded">絞り込み</button>
    </form>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="px-4 py-2">ユーザー</th>
                <th class="px-4 py-2">合計工数 (h)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($summary as $row)
            <tr>
                <td class="border px-4 py-2">{{ $row->user->name ?? $row->user_id }}</td>
                <td class="border px-4 py-2">{{ $row->total_hours }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
