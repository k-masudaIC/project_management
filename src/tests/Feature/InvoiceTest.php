<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_generate_monthly_invoices(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create([
            'role' => 'contractor',
            'rate_type' => 'hourly',
            'hourly_rate' => 3000,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post('/invoices/generate-monthly', [
            'month' => now()->format('Y-m'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('invoices', 1);
    }

    public function test_contractor_can_only_view_own_invoice(): void
    {
        $contractor = User::factory()->create(['role' => 'contractor']);
        $other = User::factory()->create(['role' => 'contractor']);

        $mine = Invoice::create([
            'invoice_number' => 'INV-202606-0001',
            'user_id' => $contractor->id,
            'billing_month' => now()->startOfMonth()->toDateString(),
            'total_hours' => 10,
            'rate_type' => 'hourly',
            'unit_rate' => 3000,
            'amount' => 30000,
            'status' => 'issued',
        ]);

        $others = Invoice::create([
            'invoice_number' => 'INV-202606-0002',
            'user_id' => $other->id,
            'billing_month' => now()->startOfMonth()->toDateString(),
            'total_hours' => 10,
            'rate_type' => 'hourly',
            'unit_rate' => 3000,
            'amount' => 30000,
            'status' => 'issued',
        ]);

        $this->actingAs($contractor)
            ->get('/invoices/' . $mine->id)
            ->assertOk();

        $this->actingAs($contractor)
            ->get('/invoices/' . $others->id)
            ->assertForbidden();
    }

    public function test_pm_can_mark_invoice_paid(): void
    {
        $pm = User::factory()->create(['role' => 'pm']);
        $contractor = User::factory()->create(['role' => 'contractor']);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-202606-0100',
            'user_id' => $contractor->id,
            'billing_month' => now()->startOfMonth()->toDateString(),
            'total_hours' => 10,
            'rate_type' => 'hourly',
            'unit_rate' => 3000,
            'amount' => 30000,
            'status' => 'issued',
        ]);

        $this->actingAs($pm)
            ->post('/invoices/' . $invoice->id . '/mark-paid')
            ->assertRedirect();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'paid',
        ]);
    }

    public function test_contractor_cannot_mark_invoice_paid(): void
    {
        $contractor = User::factory()->create(['role' => 'contractor']);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-202606-0101',
            'user_id' => $contractor->id,
            'billing_month' => now()->startOfMonth()->toDateString(),
            'total_hours' => 10,
            'rate_type' => 'hourly',
            'unit_rate' => 3000,
            'amount' => 30000,
            'status' => 'issued',
        ]);

        $this->actingAs($contractor)
            ->post('/invoices/' . $invoice->id . '/mark-paid')
            ->assertForbidden();
    }
}
