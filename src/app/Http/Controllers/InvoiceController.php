<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InvoiceController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Invoice::class);

        $query = Invoice::with('user')->orderByDesc('billing_month');

        if ($request->filled('month')) {
            $query->where('billing_month', $request->input('month') . '-01');
        }

        if (auth()->user()->role === 'contractor') {
            $query->where('user_id', auth()->id());
        }

        $invoices = $query->paginate(20);

        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        $invoice->load('user');

        return view('invoices.show', compact('invoice'));
    }

    public function generateMonthly(Request $request, InvoiceService $invoiceService)
    {
        $this->authorize('generate', Invoice::class);

        $month = $request->input('month', now()->subMonth()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            return back()->withErrors(['month' => '対象月は YYYY-MM 形式で入力してください。']);
        }

        $invoices = $invoiceService->generateForMonth($month);

        return redirect()->route('invoices.index', ['month' => $month])
            ->with('success', sprintf('%s の請求書を %d 件発行しました。', $month, $invoices->count()));
    }

    public function markPaid(Invoice $invoice)
    {
        $this->authorize('markPaid', Invoice::class);

        $invoice->update([
            'status' => 'paid',
        ]);

        return redirect()->route('invoices.show', $invoice)->with('success', '請求書を支払済みに更新しました。');
    }

    public function printable(Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        $invoice->load('user');

        return view('invoices.printable', compact('invoice'));
    }
}
