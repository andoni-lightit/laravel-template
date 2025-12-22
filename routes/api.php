<?php

declare(strict_types=1);

use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\Route;

use Lightit\Users\App\Controllers\{
    GetUserController,
    DeleteUserController,
    ListUserController,
    StoreUserController,
    UpdateUserController
};

use Lightit\Doctors\App\Controllers\{
    GetDoctorController,
    DeleteDoctorController,
    ListDoctorController,
    StoreDoctorController,
    UpdateDoctorController
};

use Lightit\Clinics\App\Controllers\{
    GetClinicController,
    DeleteClinicController,
    ListClinicController,
    StoreClinicController,
    UpdateClinicController
};

use Lightit\Appointments\App\Controllers\{
    DeleteAppointmentController,
    ListAppointmentController,
    StoreAppointmentController
};

use Lightit\Authentication\App\Controllers\{
    LoginController,
    RefreshController,
    LogoutController,
};


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')
    ->get('/me', fn(
        #[CurrentUser] $user
    ) => response()->json([
        'data' => $user,
    ]));

/*
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
*/
Route::prefix('users')
    ->middleware([])
    ->group(static function (): void {
        Route::get('/', ListUserController::class);
        Route::get('/{user}', GetUserController::class)
            ->withTrashed()
            ->whereNumber('user');
        Route::post('/', StoreUserController::class);
        Route::put('/{user}', UpdateUserController::class)
            ->whereNumber('user');
        Route::delete('/{user}', DeleteUserController::class)
            ->whereNumber('user');
    });

/*
|--------------------------------------------------------------------------
| Doctors Routes
|--------------------------------------------------------------------------
*/
Route::prefix('doctors')->group(static function (): void {
    Route::get('/', ListDoctorController::class);
    Route::post('/', StoreDoctorController::class);

    Route::prefix('{doctor}')->group(static function (): void {
        Route::get('/', GetDoctorController::class)->withTrashed();
        Route::put('/', UpdateDoctorController::class);
        Route::delete('/', DeleteDoctorController::class);
    })->whereNumber('doctor');
});

/*
|--------------------------------------------------------------------------
| Clinics Routes
|--------------------------------------------------------------------------
*/
Route::prefix('clinics')->group(static function (): void {
    Route::get('/', ListClinicController::class);
    Route::post('/', StoreClinicController::class);

    Route::prefix('{clinic}')->group(static function (): void {
        Route::get('/', GetClinicController::class)->withTrashed();
        Route::put('/', UpdateClinicController::class);
        Route::delete('/', DeleteClinicController::class);
    })->whereNumber('clinic');
});

/*
|--------------------------------------------------------------------------
| Appointment Routes
|--------------------------------------------------------------------------
*/
Route::prefix('appointments')->middleware(['auth'])->group(static function (): void {
    Route::get('/', ListAppointmentController::class);
    Route::post('/', StoreAppointmentController::class);

    Route::prefix('{appointment}')->group(static function (): void {
        Route::delete('/', DeleteAppointmentController::class);
    })->whereNumber('appointment');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(static function (): void {
    Route::post('login', LoginController::class);

    Route::middleware(['auth'])->group(static function (): void {
        Route::post('logout', LogoutController::class);
        Route::post('refresh', RefreshController::class);
    });
});

