<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id', 'name', 'code', 'description', 'status', 'budget', 'estimated_hours', 'start_date', 'end_date', 'created_by'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'proposal' => '提案中',
            'in_progress' => '進行中',
            'on_hold' => '保留',
            'completed' => '完了',
            'cancelled' => 'キャンセル',
            default => $this->status,
        };
    }
}
