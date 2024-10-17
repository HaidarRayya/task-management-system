<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Access\Response;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('start-work-task', function ($user, int $assigned_to) {
            return $user->id  == $assigned_to ?
                Response::allow()
                : Response::deny('لا يمكنك القيام بهذه العملية');
        });
        Gate::define('end-work-task', function ($user, int $assigned_to) {
            return $user->id  == $assigned_to ?
                Response::allow()
                : Response::deny('لا يمكنك القيام بهذه العملية');
        });
        Gate::define('start-test-task', function ($user, int $assigned_to) {
            return $user->id  == $assigned_to ?
                Response::allow()
                : Response::deny('لا يمكنك القيام بهذه العملية');
        });
        Gate::define('end-test-task', function ($user, int $assigned_to) {
            return $user->id  == $assigned_to ?
                Response::allow()
                : Response::deny('لا يمكنك القيام بهذه العملية');
        });
    }
}
