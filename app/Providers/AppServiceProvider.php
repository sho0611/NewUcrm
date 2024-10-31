<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Services\SendNotificationItemNames;
use App\Interfaces\SendNotificationItemNamesInterface;
use App\Services\SaveAppointment;
use App\Interfaces\AppointmentSaverInterface;
use App\Services\SavePost;
use App\Interfaces\PostSaverInterface;
use App\Services\SaveComment;
use App\Interfaces\CommentSeverInterface;
use App\Services\SaveCoupon;
use App\Interfaces\CouponSaverInterface;

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

        $this->app->bind(PostSaverInterface::class,
        SavePost::class);

        $this->app->bind(CommentSeverInterface::class,
        SaveComment::class);

        $this->app->bind(CouponSaverInterface::class,
        SaveCoupon::class);
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
