<?php

use App\Http\Controllers\Api\Customer\AuthController;
use App\Http\Controllers\Api\Customer\CarWayBillsController;
use App\Http\Controllers\Api\Customer\GeneralController;
use App\Http\Controllers\Api\Customer\LocationsController;
use App\Http\Controllers\Api\Customer\Payment\PaymentController;
use App\Http\Controllers\Api\Customer\SMS\SmsVerifyController;
use App\Http\Controllers\Api\Customer\WayBillsController;
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

Route::group(['middleware' => ['lang'], 'namespace' => 'Api/Customer'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);


        Route::post('send-mail', [AuthController::class, 'forgot']);
        Route::post('code-check', [AuthController::class, 'checkcode']);
        Route::post('reset-password', [AuthController::class, 'reset']);
    });
    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('logout', [AuthController::class, 'logout']);

        Route::get('me', [AuthController::class, 'me']);
        Route::group(['prefix' => 'profile'], function () {
            Route::get('show', [AuthController::class, 'me']);
            Route::post('update', [AuthController::class, 'profile']);
            Route::post('changePassword', [AuthController::class, 'changePassword']);
            Route::post('change-photo', [AuthController::class, 'profileImage']);
            Route::post('delete', [AuthController::class, 'deleteAccount']);
        });
        Route::group(['prefix' => 'locations'], function () {
            Route::get('/', [LocationsController::class, 'index']);
            Route::get('/create', [LocationsController::class, 'create']);
            Route::get('/{id}', [LocationsController::class, 'show']);
            Route::put('{id}/update', [LocationsController::class, 'update']);
            Route::post('store', [LocationsController::class, 'store']);
        });
        Route::group(['prefix' => 'waybills'], function () {
            Route::get('/all', [WayBillsController::class, 'all']);
            Route::get('/', [WayBillsController::class, 'index']);
            Route::get('/create', [WayBillsController::class, 'create']);
            Route::get('/{id}', [WayBillsController::class, 'show']);
            Route::post('/store', [WayBillsController::class, 'store']);
            Route::post('{id}/cancel', [WayBillsController::class, 'cancel']);
            Route::post('{id}/set_rate', [WayBillsController::class, 'setRate']);
        });

        Route::group(['prefix' => 'car-waybills'], function () {
            Route::get('/all', [CarWayBillsController::class, 'all']);
            Route::get('/', [CarWayBillsController::class, 'index']);
            Route::get('/create', [CarWayBillsController::class, 'create']);
            Route::get('/{id}', [CarWayBillsController::class, 'show']);
            Route::post('/store', [CarWayBillsController::class, 'store']);
            Route::post('/pricing', [CarWayBillsController::class, 'pricing']);
            Route::post('/cancel/{id}', [CarWayBillsController::class, 'cancelWaybill']);
            Route::group(['prefix' => 'cars'], function () {
                Route::get('/create', [CarWayBillsController::class, 'createCar']);
                Route::post('/store', [CarWayBillsController::class, 'storeCar']);
            });
            Route::post('/add_photo', [CarWayBillsController::class, 'storePhoto']);
        });


        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('auth/logout', [AuthController::class, 'logout']);


        Route::group(['prefix' => 'payments'], function () {
            Route::post('/checkout', [PaymentController::class, 'createCheckout']);
            Route::post('/status', [PaymentController::class, 'paymentStatus']);
//            Route::get('/preview', [PaymentController::class, 'checkoutPreview']);
        });

        Route::get('terms', [GeneralController::class, 'terms']);
        Route::get('questions', [GeneralController::class, 'questions']);
        Route::get('slider', [GeneralController::class, 'slider']);

    });
    Route::get('payments/preview', [PaymentController::class, 'checkoutPreview']);
    Route::group(['prefix' => 'otp'], function () {
        Route::post('send', [SmsVerifyController::class, 'send']);
        Route::post('check', [SmsVerifyController::class, 'check']);
    });
});

