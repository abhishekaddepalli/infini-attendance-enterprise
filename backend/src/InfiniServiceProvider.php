<?php

declare(strict_types=1);

namespace Infini\Attendance;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class InfiniServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/infini.php', 'infini'
        );

        $this->registerServices();
        $this->registerRepositories();
    }

    public function boot(): void
    {
        $this->bootRoutes();
        $this->bootRateLimiting();
        $this->bootMiddleware();
        $this->bootCommands();
        $this->bootMacros();
    }

    protected function registerServices(): void
    {
        // Core Services
        $this->app->singleton(Services\AttendanceEngine::class);
        $this->app->singleton(Services\FaceRecognitionService::class);
        $this->app->singleton(Services\BiometricGatewayService::class);
        $this->app->singleton(Services\GeofenceService::class);
        $this->app->singleton(Services\DeviceSyncService::class);
        $this->app->singleton(Services\PayrollEngine::class);
        $this->app->singleton(Services\LeaveEngine::class);
        $this->app->singleton(Services\ShiftScheduler::class);
        $this->app->singleton(Services\NotificationService::class);
        $this->app->singleton(Services\PaymentGatewayService::class);
        $this->app->singleton(Services\SubscriptionService::class);
        $this->app->singleton(Services\AIEngine::class);
        $this->app->singleton(Services\ReportGenerator::class);
        $this->app->singleton(Services\WhiteLabelService::class);
        $this->app->singleton(Services\AuditService::class);
        $this->app->singleton(Services\EncryptionService::class);
        $this->app->singleton(Services\PwaService::class);
    }

    protected function registerRepositories(): void
    {
        $this->app->bind(
            Contracts\AttendanceRepositoryInterface::class,
            Repositories\AttendanceRepository::class
        );
        $this->app->bind(
            Contracts\EmployeeRepositoryInterface::class,
            Repositories\EmployeeRepository::class
        );
        $this->app->bind(
            Contracts\DeviceRepositoryInterface::class,
            Repositories\DeviceRepository::class
        );
    }

    protected function bootRoutes(): void
    {
        Route::middlewareGroup('infini', ['api', 'tenant']);

        Route::middleware(['infini', 'auth:sanctum'])
            ->prefix('api/v1')
            ->group(base_path('routes/api.php'));

        Route::middleware(['infini', 'auth:sanctum', 'rbac:super-admin'])
            ->prefix('api/v1/super-admin')
            ->group(base_path('routes/super-admin.php'));

        Route::middleware(['infini', 'auth:sanctum'])
            ->prefix('api/v1/{tenant}')
            ->group(base_path('routes/organization.php'));

        Route::middleware(['infini'])
            ->prefix('api/v1/public')
            ->group(base_path('routes/public.php'));

        Route::middleware(['infini'])
            ->prefix('api/v1/payment')
            ->group(base_path('routes/payment.php'));

        Route::middleware(['infini'])
            ->prefix('api/v1/pwa')
            ->group(base_path('routes/pwa.php'));
    }

    protected function bootRateLimiting(): void
    {
        RateLimiter::for('api', fn($request) =>
            Limit::perMinute(1000)->by($request->user()?->id ?: $request->ip())
        );
        RateLimiter::for('attendance', fn($request) =>
            Limit::perMinute(10)->by($request->user()?->id)
        );
        RateLimiter::for('face-recognition', fn($request) =>
            Limit::perMinute(5)->by($request->user()?->id)
        );
        RateLimiter::for('reports', fn($request) =>
            Limit::perHour(50)->by($request->user()?->id)
        );
    }

    protected function bootMiddleware(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('tenant', Middleware\IdentifyTenant::class);
        $router->aliasMiddleware('rbac', Middleware\CheckPermission::class);
        $router->aliasMiddleware('2fa', Middleware\TwoFactorAuthenticate::class);
        $router->aliasMiddleware('subscription', Middleware\CheckSubscription::class);
        $router->aliasMiddleware('feature', Middleware\CheckFeature::class);
    }

    protected function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\InstallCommand::class,
                Console\Commands\CreateSuperAdmin::class,
                Console\Commands\DeviceSyncCommand::class,
                Console\Commands\AttendanceSyncCommand::class,
                Console\Commands\PayrollGenerateCommand::class,
                Console\Commands\LeaveResetCommand::class,
                Console\Commands\BackupDatabaseCommand::class,
                Console\Commands\GenerateReportsCommand::class,
                Console\Commands\HealthCheckCommand::class,
            ]);
        }
    }

    protected function bootMacros(): void
    {
        \Illuminate\Http\JsonResponse::macro('success', function ($data = null, $message = 'Success', $code = 200) {
            return $this->json([
                'status' => 'success',
                'message' => $message,
                'data' => $data,
                'timestamp' => now()->toIso8601String(),
                'request_id' => request()->header('X-Request-ID'),
            ], $code);
        });

        \Illuminate\Http\JsonResponse::macro('error', function ($message = 'Error', $code = 400, $errors = null) {
            return $this->json([
                'status' => 'error',
                'message' => $message,
                'errors' => $errors,
                'timestamp' => now()->toIso8601String(),
                'request_id' => request()->header('X-Request-ID'),
            ], $code);
        });
    }

    protected function bootPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/infini.php' => config_path('infini.php'),
            ], 'infini-config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'infini-migrations');
        }
    }
}
