<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
