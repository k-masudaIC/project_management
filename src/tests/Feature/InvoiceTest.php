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
}
