@extends('layouts.app')
@section('title', '案件別収支レポート')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">案件別収支レポート</h1>
    <form method="GET" class="mb-4 flex gap-2">
        <input type="month" name="month" value="{{ $selectedMonth ?? request('month') }}" class="border rounded px-2 py-1">
        <input type="text" name="project_code" value="{{ $selectedProjectCode ?? request('project_code') }}" class="border rounded px-2 py-1" placeholder="案件コード">
        <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">検索</button>
    </form>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-2 py-1">案件コード</th>
                <th class="border px-2 py-1">案件名</th>
                <th class="border px-2 py-1">予算</th>
                <th class="border px-2 py-1">実績工数</th>
                <th class="border px-2 py-1">消化率</th>
                <th class="border px-2 py-1">収支</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportRows as $row)
            <tr>
                <td class="border px-2 py-1">{{ $row['project_code'] }}</td>
                <td class="border px-2 py-1">{{ $row['project_name'] }}</td>
                <td class="border px-2 py-1 text-right">{{ number_format($row['budget'], 0) }}</td>
                <td class="border px-2 py-1 text-right">{{ number_format($row['actual_hours'], 2) }}</td>
                <td class="border px-2 py-1 text-right">{{ number_format($row['burn_rate'], 1) }}%</td>
                <td class="border px-2 py-1 text-right">{{ number_format($row['balance'], 0) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-gray-400">データがありません</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        @can('export-report')
        <a href="{{ route('reports.project', array_merge(request()->query(), ['export' => 'csv'])) }}" class="bg-green-500 text-white px-4 py-1 rounded">CSVエクスポート</a>
        @endcan
    </div>
</div>
@endsection
