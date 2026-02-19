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
        // 他のモデルポリシー
        // 'App\\Models\\Model' => 'App\\Policies\\ModelPolicy',
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
