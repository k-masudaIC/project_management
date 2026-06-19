@extends('layouts.app')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">請求書管理</h1>

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

    <div class="flex items-center gap-2 mb-4">
        <form method="GET" class="flex items-center gap-2">
            <input type="month" name="month" value="{{ request('month') }}" class="border rounded px-2 py-1">
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">絞り込み</button>
        </form>

        @can('generate', App\Models\Invoice::class)
            <form method="POST" action="{{ route('invoices.generate-monthly') }}" class="flex items-center gap-2 ml-auto">
                @csrf
                <input type="month" name="month" value="{{ request('month', now()->subMonth()->format('Y-m')) }}" class="border rounded px-2 py-1">
                <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded">月次請求書を発行</button>
            </form>
        @endcan
    </div>

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-2 py-1">請求書番号</th>
                <th class="border px-2 py-1">対象月</th>
                <th class="border px-2 py-1">担当者</th>
                <th class="border px-2 py-1">工数</th>
                <th class="border px-2 py-1">金額</th>
                <th class="border px-2 py-1">状態</th>
                <th class="border px-2 py-1">操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td class="border px-2 py-1">{{ $invoice->invoice_number }}</td>
                    <td class="border px-2 py-1">{{ $invoice->billing_month?->format('Y-m') }}</td>
                    <td class="border px-2 py-1">{{ $invoice->user->name ?? '-' }}</td>
                    <td class="border px-2 py-1 text-right">{{ number_format($invoice->total_hours, 2) }}</td>
                    <td class="border px-2 py-1 text-right">{{ number_format($invoice->amount, 0) }}</td>
                    <td class="border px-2 py-1">{{ $invoice->status }}</td>
                    <td class="border px-2 py-1"><a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 underline">詳細</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-gray-400 py-4">請求書がありません</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $invoices->links() }}</div>
</div>
@endsection
