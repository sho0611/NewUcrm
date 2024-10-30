<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Services\SendNotificationItemNames;
use App\Interfaces\SendNotificationItemNamesInterface;
use App\Services\SaveAppointment;
use App\Interfaces\AppointmentSaverInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SendNotificationItemNamesInterface::class,
        SendNotificationItemNames::class);
        $this->app->bind(AppointmentSaverInterface::class,
        SaveAppointment::class);
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
