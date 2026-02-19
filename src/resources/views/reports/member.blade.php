@extends('layouts.app')
@section('title', 'メンバー別稼働率レポート')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">メンバー別稼働率レポート</h1>
    <form method="GET" class="mb-4 flex gap-2">
        <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}" class="border rounded px-2 py-1">
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
            {{-- @foreach($reportRows as $row) --}}
            <tr>
                <td class="border px-2 py-1">（例）山田太郎</td>
                <td class="border px-2 py-1 text-right">160</td>
                <td class="border px-2 py-1 text-right">80%</td>
            </tr>
            {{-- @endforeach --}}
        </tbody>
    </table>
    <div class="mt-4">
        <a href="?export=csv" class="bg-green-500 text-white px-4 py-1 rounded">CSVエクスポート</a>
        <a href="?export=pdf" class="bg-gray-700 text-white px-4 py-1 rounded ml-2">PDFエクスポート</a>
    </div>
</div>
@endsection
