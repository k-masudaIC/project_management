<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\ReportPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Client::class => \App\Policies\ClientPolicy::class,
        \App\Models\Project::class => \App\Policies\ProjectPolicy::class,
        \App\Models\Task::class => \App\Policies\TaskPolicy::class,
        \App\Models\TaskAssignment::class => \App\Policies\TaskAssignmentPolicy::class,
        \App\Models\TimeEntry::class => \App\Policies\TimeEntryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // ReportPolicyのGate登録
        Gate::define('view-report', [ReportPolicy::class, 'view']);
        Gate::define('export-report', [ReportPolicy::class, 'export']);
    }
}
