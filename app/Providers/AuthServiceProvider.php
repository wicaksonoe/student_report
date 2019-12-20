<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('pengurus', function($user) {
					if($user->role == 'pengurus') {
						return True;
					} else {
						return False;
					}
				});

				Gate::define('guru', function($user) {
					if($user->role == 'guru') {
						return True;
					} else {
						return False;
					}
				});
    }
}
