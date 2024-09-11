<?php

use App\Http\Controllers\Backend\BookingDataController;
use App\Http\Controllers\Backend\BookingTrashController;
use App\Http\Controllers\Backend\DataCompanyController;
use App\Http\Controllers\Backend\DataFastboatController;
use App\Http\Controllers\Backend\DataRouteController;
use App\Http\Controllers\Backend\FastboatAvailabilityController;
use App\Http\Controllers\Backend\MasterCurrencyController;
use App\Http\Controllers\Backend\MasterIslandController;
use App\Http\Controllers\Backend\MasterNationalityController;
use App\Http\Controllers\Backend\MasterPaymentController;
use App\Http\Controllers\Backend\MasterPaymentMethodController;
use App\Http\Controllers\Backend\MasterPortController;
use App\Http\Controllers\Backend\SchedulesScheduleController;
use App\Http\Controllers\Backend\SchedulesShuttleAreaController;
use App\Http\Controllers\Backend\SchedulesShuttleController;
use App\Http\Controllers\Backend\SchedulesTripController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::get('/register', function () {
    return redirect('/');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::prefix('booking')->group(function () {
        //booking-data routes
        Route::get('/data', [BookingDataController::class, 'index'])->name('data.view');
        Route::get('/data/add', [BookingDataController::class, 'add'])->name('data.add');
        Route::post('/data/store', [BookingDataController::class, 'store'])->name('data.store');
        Route::get('/data/edit/{id}', [BookingDataController::class, 'edit'])->name('data.edit');
        Route::post('/data/update/{id}', [BookingDataController::class, 'update'])->name('data.update');
        Route::delete('/data/delete/{id}', [BookingDataController::class, 'delete'])->name('data.delete');
        Route::get('/data/search', [BookingDataController::class, 'search'])->name('data.search');

        //booking-trash route
        Route::get('/trash', [BookingTrashController::class, 'index'])->name('trash.view');
        Route::get('/trash/add', [BookingTrashController::class, 'add'])->name('trash.add');
        Route::post('/trash/store', [BookingTrashController::class, 'store'])->name('trash.store');
        Route::get('/trash/edit/{id}', [BookingTrashController::class, 'edit'])->name('trash.edit');
        Route::post('/trash/update/{id}', [BookingTrashController::class, 'update'])->name('trash.update');
        Route::delete('/trash/delete/{id}', [BookingTrashController::class, 'delete'])->name('trash.delete');
    });

    Route::prefix('data')->group(function () {
        // company routes
        Route::get('/company', [DataCompanyController::class, 'index'])->name('company.view');
        Route::get('/company/add', [DataCompanyController::class, 'add'])->name('company.add');
        Route::post('/company/store', [DataCompanyController::class, 'store'])->name('company.store');
        Route::get('/company/edit/{id}', [DataCompanyController::class, 'edit'])->name('company.edit');
        Route::post('/company/update/{id}', [DataCompanyController::class, 'update'])->name('company.update');
        Route::delete('/company/delete/{id}', [DataCompanyController::class, 'delete'])->name('company.delete');
        Route::get('/company/{id}', [DataCompanyController::class, 'show'])->name('company.show');
        Route::get('/company/emailstatus/{id}', [DataCompanyController::class, 'emailStatus'])->name('company.emailStatus');
        Route::get('/company/status/{id}', [DataCompanyController::class, 'companyStatus'])->name('company.status');

        // fast-boat routes
        Route::get('/fast-boat', [DataFastboatController::class, 'index'])->name('fast-boat.view');
        Route::get('/fast-boat/add', [DataFastboatController::class, 'add'])->name('fast-boat.add');
        Route::post('/fast-boat/store', [DataFastboatController::class, 'store'])->name('fast-boat.store');
        Route::get('/fast-boat/edit/{id}', [DataFastboatController::class, 'edit'])->name('fast-boat.edit');
        Route::post('/fast-boat/update/{id}', [DataFastboatController::class, 'update'])->name('fast-boat.update');
        Route::delete('/fast-boat/delete/{id}', [DataFastboatController::class, 'delete'])->name('fast-boat.delete');
        Route::get('/fast-boat/{id}', [DataFastboatController::class, 'show'])->name('fast-boat.show');
        Route::get('/fast-boat/status/{id}', [DataFastboatController::class, 'status'])->name('fast-boat.status');

        // route routes
        Route::get('/route', [DataRouteController::class, 'index'])->name('route.view');
        Route::get('/route/add', [DataRouteController::class, 'add'])->name('route.add');
        Route::post('/route/store', [DataRouteController::class, 'store'])->name('route.store');
        Route::get('/route/edit/{id}', [DataRouteController::class, 'edit'])->name('route.edit');
        Route::post('/route/update/{id}', [DataRouteController::class, 'update'])->name('route.update');
        Route::delete('/route/delete/{id}', [DataRouteController::class, 'delete'])->name('route.delete');
    });

    Route::prefix('schedules')->group(function () {
        // schedule routes
        Route::get('/schedule', [SchedulesScheduleController::class, 'index'])->name('schedule.view');
        Route::get('/schedule/add', [SchedulesScheduleController::class, 'add'])->name('schedule.add');
        Route::post('/schedule/store', [SchedulesScheduleController::class, 'store'])->name('schedule.store');
        Route::get('/schedule/edit/{id}', [SchedulesScheduleController::class, 'edit'])->name('schedule.edit');
        Route::post('/schedule/update/{id}', [SchedulesScheduleController::class, 'update'])->name('schedule.update');
        Route::delete('/schedule/delete/{id}', [SchedulesScheduleController::class, 'delete'])->name('schedule.delete');

        // trip routes
        Route::get('/trip', [SchedulesTripController::class, 'index'])->name('trip.view');
        Route::get('/trip/add', [SchedulesTripController::class, 'add'])->name('trip.add');
        Route::post('/trip/store', [SchedulesTripController::class, 'store'])->name('trip.store');
        Route::get('/trip/edit/{id}', [SchedulesTripController::class, 'edit'])->name('trip.edit');
        Route::post('/trip/update/{id}', [SchedulesTripController::class, 'update'])->name('trip.update');
        Route::delete('/trip/delete/{id}', [SchedulesTripController::class, 'delete'])->name('trip.delete');
        Route::get('/trip/{id}', [SchedulesTripController::class, 'show'])->name('trip.show');
        Route::get('/trip/status/{id}', [SchedulesTripController::class, 'status'])->name('trip.status');

        // shuttle area routes
        Route::get('/shuttlearea', [SchedulesShuttleAreaController::class, 'index'])->name('shuttlearea.view');
        Route::get('/shuttlearea/add', [SchedulesShuttleAreaController::class, 'add'])->name('shuttlearea.add');
        Route::post('/shuttlearea/store', [SchedulesShuttleAreaController::class, 'store'])->name('shuttlearea.store');
        Route::get('/shuttlearea/edit/{id}', [SchedulesShuttleAreaController::class, 'edit'])->name('shuttlearea.edit');
        Route::post('/shuttlearea/update/{id}', [SchedulesShuttleAreaController::class, 'update'])->name('shuttlearea.update');
        Route::delete('/shuttlearea/delete/{id}', [SchedulesShuttleAreaController::class, 'delete'])->name('shuttlearea.delete');

        // shuttle routes
        Route::get('/shuttle', [SchedulesShuttleController::class, 'index'])->name('shuttle.view');
        Route::get('/shuttle/add', [SchedulesShuttleController::class, 'add'])->name('shuttle.add');
        Route::post('/shuttle/store', [SchedulesShuttleController::class, 'store'])->name('shuttle.store');
        Route::get('/shuttle/edit/{id}', [SchedulesShuttleController::class, 'edit'])->name('shuttle.edit');
        Route::post('/shuttle/update/{id}', [SchedulesShuttleController::class, 'update'])->name('shuttle.update');
        Route::post('/shuttle/multiple', [SchedulesShuttleController::class, 'multiple'])->name('shuttle.multiple');
        Route::post('/shuttle/search', [SchedulesShuttleController::class, 'search'])->name('shuttle.search');
        Route::post('/shuttle/delete-multiple', [SchedulesShuttleController::class, 'deleteMultiple'])->name('shuttle.deleteMultiple');
    });

    Route::prefix('fast-boat')->group(function () {
        // fast-boat availability
        Route::get('/availability', [FastboatAvailabilityController::class, 'index'])->name('availability.view');
        Route::get('/availability/add', [FastboatAvailabilityController::class, 'add'])->name('availability.add');
        Route::post('/availability/store', [FastboatAvailabilityController::class, 'store'])->name('availability.store');
        Route::get('/availability/edit', [FastboatAvailabilityController::class, 'edit'])->name('availability.edit');
        Route::put('/availability/update', [FastboatAvailabilityController::class, 'update'])->name('availability.update');
        Route::delete('/availability/delete/{id}', [FastboatAvailabilityController::class, 'delete'])->name('availability.delete');
        Route::get('/availability/extend', [FastboatAvailabilityController::class, 'extend'])->name('availability.extend');
        Route::post('/availability/storeExtend', [FastboatAvailabilityController::class, 'storeExtend'])->name('availability.storeExtend');
        Route::get('/availability/{id}', [FastboatAvailabilityController::class, 'show'])->name('availability.show');
    });

    Route::prefix('master')->group(function () {
        // master island routes
        Route::get('/island', [MasterIslandController::class, 'index'])->name('island.view');
        Route::get('/island/add', [MasterIslandController::class, 'add'])->name('island.add');
        Route::post('/island/store', [MasterIslandController::class, 'store'])->name('island.store');
        Route::get('/island/edit/{id}', [MasterIslandController::class, 'edit'])->name('island.edit');
        Route::post('/island/update/{id}', [MasterIslandController::class, 'update'])->name('island.update');
        Route::delete('/island/delete/{id}', [MasterIslandController::class, 'delete'])->name('island.delete');
        Route::get('/island/{id}', [MasterIslandController::class, 'show'])->name('island.show');

        // master port routes
        Route::get('/port', [MasterPortController::class, 'index'])->name('port.view');
        Route::get('/port/add', [MasterPortController::class, 'add'])->name('port.add');
        Route::post('/port/store', [MasterPortController::class, 'store'])->name('port.store');
        Route::get('/port/edit/{id}', [MasterPortController::class, 'edit'])->name('port.edit');
        Route::post('/port/update/{id}', [MasterPortController::class, 'update'])->name('port.update');
        Route::delete('/port/delete/{id}', [MasterPortController::class, 'delete'])->name('port.delete');
        Route::get('/port/{id}', [MasterPortController::class, 'show'])->name('port.show');

        // master payment
        Route::get('/payment-method', [MasterPaymentMethodController::class, 'index'])->name('payment-method.view');
        Route::post('/payment-method/store', [MasterPaymentMethodController::class, 'store'])->name('payment-method.store');
        Route::post('/payment-method/update/{id}', [MasterPaymentMethodController::class, 'update'])->name('payment-method.update');
        Route::delete('/payment-method/delete/{id}', [MasterPaymentMethodController::class, 'delete'])->name('payment-method.delete');

        // master nationality
        Route::get('/nationality', [MasterNationalityController::class, 'index'])->name('nationality.view');
        Route::post('/nationality/store', [MasterNationalityController::class, 'store'])->name('nationality.store');
        Route::post('/nationality/update/{id}', [MasterNationalityController::class, 'update'])->name('nationality.update');
        Route::delete('/nationality/delete/{id}', [MasterNationalityController::class, 'delete'])->name('nationality.delete');

        // master currency
        Route::get('/currency', [MasterCurrencyController::class, 'index'])->name('currency.view');
        Route::post('/currency/store', [MasterCurrencyController::class, 'store'])->name('currency.store');
        Route::post('/currency/updateKurs', [MasterCurrencyController::class, 'updateKurs'])->name('currency.updateKurs');
        Route::delete('/currency/delete/{id}', [MasterCurrencyController::class, 'delete'])->name('currency.delete');
        Route::get('/currency/status/{id}', [MasterCurrencyController::class, 'currencyStatus'])->name('currency.status');
    });

    Route::post('api/fetch-fastboat', [FastboatAvailabilityController::class, 'fetchFastboat']);
    Route::post('api/fetch-schedule', [FastboatAvailabilityController::class, 'fetchSchedule']);

    Route::get('/getFilteredData', [BookingDataController::class, 'getFilteredData']);
});
