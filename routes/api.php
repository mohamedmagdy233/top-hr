<?php

use App\Http\Controllers\Api\{
    AuthController,
    UserController
};
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class,'login']);

    ##|> jwt Routes
    Route::group(['middleware' => 'jwt'],function () {
        Route::post('logout', [AuthController::class,'logout']);

        ##|> jwt Routes

        // for employee
        Route::get('home',[UserController::class,'home']);
        Route::post('checkin',[UserController::class,'checkin']);
        Route::post('checkout',[UserController::class,'checkout']);
        Route::get('getUserMonths',[UserController::class,'userMonths']);
        Route::post('orderAnAdvance',[UserController::class,'orderAnAdvance']);
        Route::get('getMyAnAdvance',[UserController::class,'getMyAnAdvance']);
        Route::post('editEmployeeProfile',[UserController::class,'editEmployeeProfile']);
        Route::post('contact-us',[UserController::class,'contact_us']);
        Route::get('myEmployeeProfile',[UserController::class,'myEmployeeProfile']);
        Route::get('getNotification',[UserController::class,'getNotification']);
        Route::get('GetNotes',[UserController::class,'GetNotes']);

        //new
        Route::get('holidaysType',[UserController::class,'holidaysType']);
        Route::get('myHolidays',[UserController::class,'myHolidays']);
        Route::post('orderHoliday',[UserController::class,'orderHoliday']);


        //for hr

        Route::post('homeHR',[UserController::class,'homeHR']);
        Route::post('getHolidaysForEmployee',[UserController::class,'getHolidaysForEmployee']);
        Route::post('AcceptOrRejectTheHoliday',[UserController::class,'AcceptOrRejectTheHoliday']);
        Route::post('getDeduction',[UserController::class,'getDeduction']);
        Route::post('AttendanceAndDeparture',[UserController::class,'AttendanceAndDeparture']);




        Route::post('storeFcm',[UserController::class,'storeFcm']);


    });

    // for testing
    Route::post('testFcm',[UserController::class,'testFcm']);
});
