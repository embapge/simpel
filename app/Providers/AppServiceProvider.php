<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
    }
}
