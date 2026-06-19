<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\TimeEntry;
use App\Models\User;
use App\Notifications\InvoiceIssuedNotification;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class InvoiceService
{
    public function generateForMonth(string $month): Collection
    {
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $end = (clone $start)->endOfMonth();

        $contractors = User::query()
            ->where('role', 'contractor')
            ->where('is_active', true)
            ->get();

        return $contractors->map(function (User $user) use ($start, $end) {
            $totalHours = (float) TimeEntry::query()
                ->where('user_id', $user->id)
                ->whereBetween('work_date', [$start->toDateString(), $end->toDateString()])
                ->sum('hours');

            $rateType = $user->rate_type ?? 'hourly';
            $unitRate = $rateType === 'daily'
                ? (float) ($user->daily_rate ?? 0)
                : (float) ($user->hourly_rate ?? 0);

            $amount = $rateType === 'daily'
                ? round(($unitRate / 8) * $totalHours, 2)
                : round($unitRate * $totalHours, 2);

            $invoice = Invoice::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'billing_month' => $start->toDateString(),
                ],
                [
                    'invoice_number' => $this->invoiceNumber($start, $user->id),
                    'total_hours' => round($totalHours, 2),
                    'rate_type' => $rateType,
                    'unit_rate' => $unitRate,
                    'amount' => $amount,
                    'status' => 'issued',
                    'issued_at' => now()->toDateString(),
                    'due_date' => $end->copy()->addDays(30)->toDateString(),
                    'auto_generated' => true,
                ]
            );

            if ($invoice->wasRecentlyCreated || $invoice->wasChanged(['total_hours', 'unit_rate', 'amount', 'status'])) {
                $user->notify(new InvoiceIssuedNotification($invoice));
            }

            return $invoice;
        });
    }

    private function invoiceNumber(Carbon $start, int $userId): string
    {
        return sprintf('INV-%s-%04d', $start->format('Ym'), $userId);
    }
}
