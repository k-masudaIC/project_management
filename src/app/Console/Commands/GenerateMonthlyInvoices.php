<?php

namespace App\Console\Commands;

use App\Services\InvoiceService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMonthlyInvoices extends Command
{
    protected $signature = 'invoices:generate-monthly {month? : 対象月 (YYYY-MM)}';

    protected $description = '業務委託の月次請求書を自動発行します';

    public function handle(InvoiceService $invoiceService): int
    {
        $month = $this->argument('month') ?: Carbon::now()->subMonth()->format('Y-m');

        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $this->error('month は YYYY-MM 形式で指定してください。');
            return self::INVALID;
        }

        $invoices = $invoiceService->generateForMonth($month);
        $this->info(sprintf('%s の請求書を %d 件発行しました。', $month, $invoices->count()));

        return self::SUCCESS;
    }
}
