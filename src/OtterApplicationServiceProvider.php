<?php

namespace Poowf\Otter;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class OtterApplicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->authorization();
    }

    /**
     * Configure the Otter authorization services.
     *
     * @return void
     */
    protected function authorization()
    {
        $this->gate();

        Otter::auth(function ($request) {
            return app()->environment('local') ||
                Gate::check('viewOtter', [$request->user()]);
        });
    }

    /**
     * Register the Otter gate.
     *
     * This gate determines who can access Otter in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewOtter', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}