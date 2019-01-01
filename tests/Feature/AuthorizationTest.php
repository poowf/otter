<?php

namespace Poowf\Otter\Tests\Feature;

use Poowf\Otter\Otter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Poowf\Otter\Tests\FeatureTestCase;
use Illuminate\Contracts\Auth\Authenticatable;
use Poowf\Otter\OtterApplicationServiceProvider;
use Orchestra\Testbench\Http\Middleware\VerifyCsrfToken;

class AuthorizationTest extends FeatureTestCase
{
    protected function getPackageProviders($app)
    {
        return array_merge(
            parent::getPackageProviders($app),
            [OtterApplicationServiceProvider::class]
        );
    }

    protected function setUp()
    {
        parent::setUp();

        $this->artisan('otter:install');
        $this->withoutMiddleware([VerifyCsrfToken::class]);
    }

    protected function tearDown()
    {
        parent::tearDown();

        Otter::auth(null);
    }

    public function test_clean_otter_installation_denies_access_by_default()
    {
        $this->get('/otter')
            ->assertStatus(403);
    }

    public function test_clean_otter_installation_denies_access_by_default_for_any_auth_user()
    {
        $this->actingAs(new Authenticated);

        $this->get('/otter')
            ->assertStatus(403);
    }

    public function test_guests_gets_unauthorized_by_gate()
    {
        Otter::auth(function (Request $request) {
            return Gate::check('viewOtter', [$request->user()]);
        });

        Gate::define('viewOtter', function ($user) {
            return true;
        });

        $this->get('/otter')
            ->assertStatus(403);
    }

    public function test_authenticated_user_gets_authorized_by_gate()
    {
        $this->actingAs(new Authenticated);

        Otter::auth(function (Request $request) {
            return Gate::check('viewOtter', [$request->user()]);
        });

        Gate::define('viewOtter', function (Authenticatable $user) {
            return $user->getAuthIdentifier() === 'otter-test';
        });

        $this->get('/otter')
            ->assertStatus(200);
    }

    public function test_guests_can_be_authorized()
    {
        Otter::auth(function (Request $request) {
            return Gate::check('viewOtter', [$request->user()]);
        });

        Gate::define('viewOtter', function (?Authenticatable $user) {
            return true;
        });

        $this->get('/otter')
            ->assertStatus(200);
    }

    public function test_unauthorized_requests()
    {
        Otter::auth(function () {
            return false;
        });

        $this->get('/otter')
            ->assertStatus(403);
    }

    public function test_authorized_requests()
    {
        Otter::auth(function () {
            return true;
        });

        $this->get('/otter')
            ->assertSuccessful();
    }
}

class Authenticated implements Authenticatable
{
    public $email;

    public function getAuthIdentifierName()
    {
        return 'Otter Test';
    }

    public function getAuthIdentifier()
    {
        return 'otter-test';
    }

    public function getAuthPassword()
    {
        return 'secret';
    }

    public function getRememberToken()
    {
        return 'otter-ottermatic';
    }

    public function setRememberToken($value)
    {
        //
    }

    public function getRememberTokenName()
    {
        //
    }
}
