<?php

namespace App\Providers;

use App\Models\RoomReservation;
use Illuminate\Support\Facades\Schema;
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
        view()->composer('*', function ($view) {
            $reservationCount = 0;

            if (Schema::hasTable('room_reservations')) {
                $reservationCount = RoomReservation::where('date', '>=', now()->toDateString())->count();
            }

            $view->with('roomReservationCount', $reservationCount);
        });
    }
}
