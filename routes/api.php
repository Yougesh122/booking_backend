<?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\BookingController;
    use App\Http\Controllers\BookingLogController;

    Route::prefix('v1')->group(function () {
        Route::get('/bookings', [BookingController::class, 'index']);
        Route::get('/get-bookings-status', [BookingController::class, 'getBookingStatus']);
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::get('/bookings/{id}', [BookingController::class, 'show']);
        Route::put('/bookings/{id}', [BookingController::class, 'update']);
        Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
        Route::get('/analytics/bookings', [BookingLogController::class, 'index']);

    });

?>
