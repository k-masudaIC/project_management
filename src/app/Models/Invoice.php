<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'billing_month',
        'total_hours',
        'rate_type',
        'unit_rate',
        'amount',
        'status',
        'issued_at',
        'due_date',
        'auto_generated',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'billing_month' => 'date',
            'issued_at' => 'date',
            'due_date' => 'date',
            'auto_generated' => 'boolean',
            'total_hours' => 'decimal:2',
            'unit_rate' => 'decimal:2',
            'amount' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
