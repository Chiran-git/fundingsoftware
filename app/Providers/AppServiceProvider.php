<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // We don't want json resources to be wrapped in "data" keys
        JsonResource::withoutWrapping();

        $this->defineBladeDirectives();

        Schema::defaultStringLength(191);
    }

    /**
     * Method to define custom blade directives
     *
     * @return void
     */
    private function defineBladeDirectives()
    {
        // Define a blade directive for formatting money
        Blade::directive('money', function ($amount, $symbol) {
            return "<?php echo $symbol . number_format($amount, 2); ?>";
        });
    }
}
