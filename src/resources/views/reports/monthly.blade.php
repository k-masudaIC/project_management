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
        <select name="client_id" class="border rounded px-2 py-1">
            <option value="">全クライアント</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected(($selectedClientId ?? '') == $client->id)>{{ $client->company_name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">絞り込み</button>
    </form>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-2 py-1">メンバー</th>
                <th class="border px-2 py-1">クライアント</th>
                <th class="border px-2 py-1">案件コード</th>
                <th class="border px-2 py-1">案件名</th>
                <th class="border px-2 py-1">合計工数</th>
                <th class="border px-2 py-1">原価（円）</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportRows as $row)
                <tr>
                    <td class="border px-2 py-1">{{ $row['user_name'] }}</td>
                    <td class="border px-2 py-1">{{ $row['client_name'] }}</td>
                    <td class="border px-2 py-1">{{ $row['project_code'] }}</td>
                    <td class="border px-2 py-1">{{ $row['project_name'] }}</td>
                    <td class="border px-2 py-1 text-right">{{ number_format($row['total_hours'], 2) }}</td>
                    <td class="border px-2 py-1 text-right">{{ number_format($row['total_cost'], 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-gray-400">データがありません</td></tr>
            @endforelse
        </tbody>
    </table>
    <h2 class="text-xl font-semibold mt-6 mb-2">案件別原価サマリー</h2>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-2 py-1">案件コード</th>
                <th class="border px-2 py-1">案件名</th>
                <th class="border px-2 py-1">合計工数</th>
                <th class="border px-2 py-1">原価（円）</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projectTotals as $row)
                <tr>
                    <td class="border px-2 py-1">{{ $row['project_code'] }}</td>
                    <td class="border px-2 py-1">{{ $row['project_name'] }}</td>
                    <td class="border px-2 py-1 text-right">{{ number_format($row['total_hours'], 2) }}</td>
                    <td class="border px-2 py-1 text-right">{{ number_format($row['total_cost'], 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-gray-400">データがありません</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        @can('export-report')
        <a href="{{ route('reports.monthly', array_merge(request()->query(), ['export' => 'csv'])) }}" class="bg-green-500 text-white px-4 py-1 rounded">CSVエクスポート</a>
        @endcan
    </div>
</div>
@endsection
