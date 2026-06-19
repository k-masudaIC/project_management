@extends('layouts.app')
@section('title', 'メンバー別稼働率レポート')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">メンバー別稼働率レポート</h1>
    <form method="GET" class="mb-4 flex gap-2">
        <input type="month" name="month" value="{{ $selectedMonth ?? request('month', now()->format('Y-m')) }}" class="border rounded px-2 py-1">
        <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">絞り込み</button>
    </form>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-2 py-1">メンバー</th>
                <th class="border px-2 py-1">稼働時間</th>
                <th class="border px-2 py-1">稼働率</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportRows as $row)
            <tr>
                <td class="border px-2 py-1">{{ $row['user_name'] }}</td>
                <td class="border px-2 py-1 text-right">{{ number_format($row['worked_hours'], 2) }}</td>
                <td class="border px-2 py-1 text-right">{{ number_format($row['utilization_rate'], 1) }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center text-gray-400">データがありません</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        @can('export-report')
        <a href="{{ route('reports.member', array_merge(request()->query(), ['export' => 'csv'])) }}" class="bg-green-500 text-white px-4 py-1 rounded">CSVエクスポート</a>
        @endcan
    </div>
</div>
@endsection
