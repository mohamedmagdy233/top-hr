<?php

use App\Http\Controllers\Admin\{AdditionController,
    AdvanceController,
    AdvanceDiscountController,
    AttendanceController,
    BranchController,
    ContactUsController,
    CustomNotificationController,
    GroupController,
    InstructionController,
    TreasuryController,
    UserController};
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () {

    Route::group(['prefix' => 'admin'], function () {
        Route::get('login', [AuthController::class, 'index'])->name('admin.login');
        Route::POST('login', [AuthController::class, 'login'])->name('admin.login');

        Route::group(['middleware' => 'auth:admin'], function () {
            Route::get('/', function () {
                return view('admin/index');
            })->name('adminHome');

            #============================ admins ==================================
            Route::resource('admins', AdminController::class);

            #============================ users ====================================
            Route::resource('users', UserController::class);
            Route::get('incentives/{user}',[UserController::class,'incentives'])->name('users.incentives');
            Route::get('/users/{user}/get-incentives/{date}', [UserController::class, 'getIncentives'])->name('users.getIncentives');
            Route::delete('destroy/incentive/{id}',[UserController::class,'deleteIncentive'])->name('incentive.destroy');
            Route::put('addIncentives',[UserController::class,'addIncentives'])->name('users.addIncentives');

            #============================ branches =================================
            Route::resource('branches', BranchController::class);

            #============================ customNotification =================================
            Route::resource('customNotification', CustomNotificationController::class);

            #============================ holidays_type =================================
            Route::resource('holidays_type', HolidayController::class);

            #============================ holidays =================================
            Route::resource('holidays', HolidayController::class);
            Route::get('holidays', [HolidayController::class, 'showHoliday'])->name('showHoliday');
            Route::get('show/holiday/details/{id}', [HolidayController::class, 'showHolidayDetails'])->name('showHolidayDetails');

            #============================ groups ===================================
            Route::resource('groups', GroupController::class);

            #============================ additions ===================================
            Route::resource('additions',AdditionController::class);

            #============================ contact-us ===================================
            Route::resource('contact-us',ContactUsController::class);

            #============================ treasures ===================================
            Route::resource('treasures',TreasuryController::class);

            #============================ revenue ===================================
            Route::resource('revenue',RevenueController::class);

            #============================ instructions ===================================
            Route::resource('instructions',InstructionController::class);

            #============================ sliders ===================================
            Route::resource('sliders',SliderController::class);
            Route::post('changeStatus',[SliderController::class,'changeStatus'])->name('changeStatus');

            #============================ expenses ===================================
            Route::resource('expenses',ExpenseController::class);

            #============================ advance-details ===================================
            Route::resource('advance-discount',AdvanceDiscountController::class);

            #============================ advances =================================
            Route::resource('advances', AdvanceController::class);
            Route::post('advances/status', [AdvanceController::class, 'updateStatus'])->name('advances.updateStatus');

            #============================ attendances ==============================
            Route::resource('attendances', AttendanceController::class);


            Route::get('my_profile', [AdminController::class, 'myProfile'])->name('myProfile');
            Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');

            #============================ Setting ==================================
            Route::get('setting', [SettingController::class, 'index'])->name('settingIndex');
            Route::POST('setting/update/{id}', [SettingController::class, 'update'])->name('settingUpdate');

        });
    });

#=======================================================================
#============================ ROOT =====================================
#=======================================================================
    Route::get('/clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('key:generate');
        Artisan::call('config:clear');
        Artisan::call('optimize:clear');
        return response()->json(['status' => 'success', 'code' => 1000000000]);
    });
});
