<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TimeEntry;

class Task extends Model
{
    use HasFactory, SoftDeletes;
    use SoftDeletes;

    protected $fillable = [
        'project_id', 'title', 'description', 'status', 'priority', 'estimated_hours', 'start_date', 'due_date', 'completed_at', 'created_by', 'sort_order'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }
        
    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    /**
     * ステータスの日本語表示
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'not_started' => '未着手',
            'in_progress' => '進行中',
            'in_review' => 'レビュー待ち',
            'completed' => '完了',
            'on_hold' => '保留',
            default => $this->status,
        };
    }

    /**
     * 優先度の日本語表示
     */
    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            'low' => '低',
            'medium' => '中',
            'high' => '高',
            default => $this->priority,
        };
    }
}
