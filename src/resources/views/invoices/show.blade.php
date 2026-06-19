@extends('layouts.app')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">請求書詳細</h1>

    <div class="bg-white border rounded p-4 space-y-2">
        <div><span class="font-semibold">請求書番号:</span> {{ $invoice->invoice_number }}</div>
        <div><span class="font-semibold">対象月:</span> {{ $invoice->billing_month?->format('Y-m') }}</div>
        <div><span class="font-semibold">担当者:</span> {{ $invoice->user->name ?? '-' }}</div>
        <div><span class="font-semibold">単価種別:</span> {{ $invoice->rate_type === 'daily' ? '日給' : '時給' }}</div>
        <div><span class="font-semibold">単価:</span> {{ number_format($invoice->unit_rate, 0) }} 円</div>
        <div><span class="font-semibold">合計工数:</span> {{ number_format($invoice->total_hours, 2) }} h</div>
        <div><span class="font-semibold">請求金額:</span> {{ number_format($invoice->amount, 0) }} 円</div>
        <div><span class="font-semibold">発行日:</span> {{ $invoice->issued_at?->format('Y-m-d') ?? '-' }}</div>
        <div><span class="font-semibold">支払期限:</span> {{ $invoice->due_date?->format('Y-m-d') ?? '-' }}</div>
        <div><span class="font-semibold">状態:</span> {{ $invoice->status }}</div>
        <div><span class="font-semibold">自動発行:</span> {{ $invoice->auto_generated ? 'はい' : 'いいえ' }}</div>
        @if($invoice->note)
            <div><span class="font-semibold">備考:</span> {{ $invoice->note }}</div>
        @endif
    </div>

    <div class="mt-4">
        <a href="{{ route('invoices.index') }}" class="text-blue-600 underline">一覧へ戻る</a>
    </div>
</div>
@endsection
