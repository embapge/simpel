<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('terbilang', function ($expression) {
            return "<?php echo Str::title(Terbilang::make($expression, ' Rupiah')); ?>";
        });

        Blade::directive('uang', function ($expression) {
            return "<?php echo 'Rp. '.number_format($expression, 0, ',','.'); ?>";
        });

        Blade::directive('paymentColor', function ($expression) {
            return "<?php echo '" . paymentColor($expression) . "' ?>";
        });

        Relation::enforceMorphMap([
            'payment' => 'App\Models\Payment',
            'user' => 'App\Models\User',
        ]);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('notification', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
