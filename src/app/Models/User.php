<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\TimeEntry;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'rate_type',
        'hourly_rate',
        'daily_rate',
        'avatar',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'role' => 'string',
            'hourly_rate' => 'decimal:2',
            'daily_rate' => 'decimal:2',
        ];
    }

    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }
    
    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class)->withTimestamps();
    }
}
