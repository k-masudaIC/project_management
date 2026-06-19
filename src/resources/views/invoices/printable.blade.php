<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>請求書 {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; color: #222; margin: 24px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 24px; }
        .title { font-size: 28px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .text-right { text-align: right; }
        .muted { color: #666; font-size: 12px; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">請求書</div>
        <div>
            <div>請求書番号: {{ $invoice->invoice_number }}</div>
            <div>対象月: {{ $invoice->billing_month?->format('Y年m月') }}</div>
        </div>
    </div>

    <div>宛名: {{ $invoice->user->name ?? '-' }} 様</div>

    <table>
        <thead>
            <tr>
                <th>項目</th>
                <th class="text-right">値</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>単価種別</td>
                <td class="text-right">{{ $invoice->rate_type === 'daily' ? '日給' : '時給' }}</td>
            </tr>
            <tr>
                <td>単価</td>
                <td class="text-right">{{ number_format($invoice->unit_rate, 0) }} 円</td>
            </tr>
            <tr>
                <td>合計工数</td>
                <td class="text-right">{{ number_format($invoice->total_hours, 2) }} h</td>
            </tr>
            <tr>
                <td>請求金額</td>
                <td class="text-right"><strong>{{ number_format($invoice->amount, 0) }} 円</strong></td>
            </tr>
            <tr>
                <td>発行日</td>
                <td class="text-right">{{ $invoice->issued_at?->format('Y-m-d') ?? '-' }}</td>
            </tr>
            <tr>
                <td>支払期限</td>
                <td class="text-right">{{ $invoice->due_date?->format('Y-m-d') ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <p class="muted">この画面は印刷用です。ブラウザの印刷機能からPDF保存できます。</p>

    <div class="no-print">
        <button onclick="window.print()">印刷 / PDF保存</button>
        <a href="{{ route('invoices.show', $invoice) }}">詳細へ戻る</a>
    </div>
</body>
</html>
