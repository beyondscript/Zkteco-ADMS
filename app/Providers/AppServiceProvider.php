<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\EloquentUserProvider;
use App\Guards\CustomSessionGuard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ExceptionHandlerContract::class, Handler::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if(Schema::hasTable('users')){
            if(!User::exists()){
                User::create([
                    'name' => 'Admin',
                    'email' => 'admin@gmail.com',
                    'password' => Hash::make('12345678')
                ]);
            }
        }

        Auth::extend(
            'custom_session_guard',
            function ($app) {
                $provider = new EloquentUserProvider($app['hash'], config('auth.providers.users.model'));

                $guard = new CustomSessionGuard('custom_session_guard', $provider, app()->make('session.store'), request());
                $guard->setCookieJar($this->app['cookie']);
                $guard->setDispatcher($this->app['events']);
                $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));
                
                return $guard;
            }
        );
    }
}
