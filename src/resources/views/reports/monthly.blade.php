@extends('layouts.app')
@section('title', '月次工数レポート')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">月次工数レポート</h1>
    <form method="GET" class="mb-4 flex gap-2">
        <input type="month" name="month" value="{{ $selectedMonth ?? now()->format('Y-m') }}" class="border rounded px-2 py-1">
        <select name="user_id" class="border rounded px-2 py-1">
            <option value="">全メンバー</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected(($selectedUserId ?? '') == $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">絞り込み</button>
    </form>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-2 py-1">メンバー</th>
                <th class="border px-2 py-1">案件コード</th>
                <th class="border px-2 py-1">案件名</th>
                <th class="border px-2 py-1">合計工数</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportRows as $row)
                <tr>
                    <td class="border px-2 py-1">{{ $row['user_name'] }}</td>
                    <td class="border px-2 py-1">{{ $row['project_code'] }}</td>
                    <td class="border px-2 py-1">{{ $row['project_name'] }}</td>
                    <td class="border px-2 py-1 text-right">{{ number_format($row['total_hours'], 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-gray-400">データがありません</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        <a href="?export=csv" class="bg-green-500 text-white px-4 py-1 rounded">CSVエクスポート</a>
        <a href="?export=pdf" class="bg-gray-700 text-white px-4 py-1 rounded ml-2">PDFエクスポート</a>
    </div>
</div>
@endsection
