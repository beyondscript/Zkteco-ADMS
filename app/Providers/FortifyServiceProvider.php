<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
use Laravel\Fortify\Http\Controllers\ConfirmedTwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\ProfileInformationController;
use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
use Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Laravel\Fortify\RoutePath;
use App\Features\Fortify\CustomFeatures;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Fortify::ignoreRoutes();

        $this->customRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.passwords.email');
        });

        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.passwords.reset', ['request' => $request]);
        });
    }

    public function customRoutes(): void
    {
        Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
            $enableViews = config('fortify.views', true);

            // Authentication...
            if ($enableViews) {
                Route::get(RoutePath::for('login', '/login'), [AuthenticatedSessionController::class, 'create'])
                    ->middleware(['guest:'.config('fortify.guard')])
                    ->name('login');
            }

            $limiter = config('fortify.limiters.login');
            $twoFactorLimiter = config('fortify.limiters.two-factor');
            $verificationLimiter = config('fortify.limiters.verification', '6,1');

            Route::post(RoutePath::for('login', '/login'), [AuthenticatedSessionController::class, 'store'])
                ->middleware(array_filter([
                    'guest:'.config('fortify.guard'),
                    $limiter ? 'throttle:'.$limiter : null,
                ]))->name('login.store');

            Route::post(RoutePath::for('logout', '/logout'), [AuthenticatedSessionController::class, 'destroy'])
                ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
                ->name('logout');

            // Password Reset...
            if (Features::enabled(Features::resetPasswords())) {
                if ($enableViews) {
                    Route::get(RoutePath::for('password.request', '/forgot-password'), [PasswordResetLinkController::class, 'create'])
                        ->middleware(['guest:'.config('fortify.guard')])
                        ->name('password.request');

                    Route::get(RoutePath::for('password.reset', '/reset-password/{token}'), [NewPasswordController::class, 'create'])
                        ->middleware(['guest:'.config('fortify.guard')])
                        ->name('password.reset');
                }

                Route::post(RoutePath::for('password.email', '/forgot-password'), [PasswordResetLinkController::class, 'store'])
                    ->middleware(['guest:'.config('fortify.guard')])
                    ->name('password.email');

                Route::post(RoutePath::for('password.update', '/reset-password'), [NewPasswordController::class, 'store'])
                    ->middleware(['guest:'.config('fortify.guard')])
                    ->name('password.update');
            }

            // Registration...
            if (Features::enabled(Features::registration())) {
                if ($enableViews) {
                    Route::get(RoutePath::for('register', '/register'), [RegisteredUserController::class, 'create'])
                        ->middleware(['guest:'.config('fortify.guard')])
                        ->name('register');
                }

                Route::post(RoutePath::for('register', '/register'), [RegisteredUserController::class, 'store'])
                    ->middleware(['guest:'.config('fortify.guard')])
                    ->name('register.store');
            }

            // Email Verification...
            if (Features::enabled(Features::emailVerification())) {
                if ($enableViews) {
                    Route::get(RoutePath::for('verification.notice', '/email/verify'), [EmailVerificationPromptController::class, '__invoke'])
                        ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
                        ->name('verification.notice');
                }

                Route::get(RoutePath::for('verification.verify', '/email/verify/{id}/{hash}'), [VerifyEmailController::class, '__invoke'])
                    ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'signed', 'throttle:'.$verificationLimiter])
                    ->name('verification.verify');

                Route::post(RoutePath::for('verification.send', '/email/verification-notification'), [EmailVerificationNotificationController::class, 'store'])
                    ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'throttle:'.$verificationLimiter])
                    ->name('verification.send');
            }

            // Profile Information...
            if (Features::enabled(Features::updateProfileInformation())) {
                Route::put(RoutePath::for('user-profile-information.update', '/user/profile-information'), [ProfileInformationController::class, 'update'])
                    ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
                    ->name('user-profile-information.update');
            }

            // Passwords...
            if (Features::enabled(Features::updatePasswords())) {
                Route::put(RoutePath::for('user-password.update', '/user/password'), [PasswordController::class, 'update'])
                    ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
                    ->name('user-password.update');
            }

            // Password Confirmation...
            if (Features::enabled(CustomFeatures::confirmPasswords())) {
                if ($enableViews) {
                    Route::get(RoutePath::for('password.confirm', '/user/confirm-password'), [ConfirmablePasswordController::class, 'show'])
                        ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
                        ->name('password.confirm');
                }

                Route::get(RoutePath::for('password.confirmation', '/user/confirmed-password-status'), [ConfirmedPasswordStatusController::class, 'show'])
                    ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
                    ->name('password.confirmation');

                Route::post(RoutePath::for('password.confirm', '/user/confirm-password'), [ConfirmablePasswordController::class, 'store'])
                    ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
                    ->name('password.confirm.store');
            }

            // Two Factor Authentication...
            if (Features::enabled(Features::twoFactorAuthentication())) {
                if ($enableViews) {
                    Route::get(RoutePath::for('two-factor.login', '/two-factor-challenge'), [TwoFactorAuthenticatedSessionController::class, 'create'])
                        ->middleware(['guest:'.config('fortify.guard')])
                        ->name('two-factor.login');
                }

                Route::post(RoutePath::for('two-factor.login', '/two-factor-challenge'), [TwoFactorAuthenticatedSessionController::class, 'store'])
                    ->middleware(array_filter([
                        'guest:'.config('fortify.guard'),
                        $twoFactorLimiter ? 'throttle:'.$twoFactorLimiter : null,
                    ]))->name('two-factor.login.store');

                $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
                    ? [config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'password.confirm']
                    : [config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')];

                Route::post(RoutePath::for('two-factor.enable', '/user/two-factor-authentication'), [TwoFactorAuthenticationController::class, 'store'])
                    ->middleware($twoFactorMiddleware)
                    ->name('two-factor.enable');

                Route::post(RoutePath::for('two-factor.confirm', '/user/confirmed-two-factor-authentication'), [ConfirmedTwoFactorAuthenticationController::class, 'store'])
                    ->middleware($twoFactorMiddleware)
                    ->name('two-factor.confirm');

                Route::delete(RoutePath::for('two-factor.disable', '/user/two-factor-authentication'), [TwoFactorAuthenticationController::class, 'destroy'])
                    ->middleware($twoFactorMiddleware)
                    ->name('two-factor.disable');

                Route::get(RoutePath::for('two-factor.qr-code', '/user/two-factor-qr-code'), [TwoFactorQrCodeController::class, 'show'])
                    ->middleware($twoFactorMiddleware)
                    ->name('two-factor.qr-code');

                Route::get(RoutePath::for('two-factor.secret-key', '/user/two-factor-secret-key'), [TwoFactorSecretKeyController::class, 'show'])
                    ->middleware($twoFactorMiddleware)
                    ->name('two-factor.secret-key');

                Route::get(RoutePath::for('two-factor.recovery-codes', '/user/two-factor-recovery-codes'), [RecoveryCodeController::class, 'index'])
                    ->middleware($twoFactorMiddleware)
                    ->name('two-factor.recovery-codes');

                Route::post(RoutePath::for('two-factor.recovery-codes', '/user/two-factor-recovery-codes'), [RecoveryCodeController::class, 'store'])
                    ->middleware($twoFactorMiddleware)
                    ->name('two-factor.regenerate-recovery-codes');
            }
        });
    }
}
