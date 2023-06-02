<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,

    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,

            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Localization::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'lang' => \App\Http\Middleware\Application\Lang::class,
//        Users App
        'UsersAll' => \App\Http\Middleware\UsersApp\All::class,
        'UsersAdd' => \App\Http\Middleware\UsersApp\Add::class,
        'UsersDelete' => \App\Http\Middleware\UsersApp\Delete::class,
        'UsersUpdate' => \App\Http\Middleware\UsersApp\Update::class,

        //Job Permissions App
        'JobPermissionsAll' => \App\Http\Middleware\JobPermissionsApp\All::class,
        'JobPermissionsAdd' => \App\Http\Middleware\JobPermissionsApp\Add::class,
        'JobPermissionsDelete' => \App\Http\Middleware\JobPermissionsApp\Delete::class,
        'JobPermissionsUpdate' => \App\Http\Middleware\JobPermissionsApp\Update::class,

// الشركات الفرعيه والرئيسيسه
        'CompaniesAll' => \App\Http\Middleware\CompaniesApp\All::class,
        'CompaniesAdd' => \App\Http\Middleware\CompaniesApp\Add::class,
        'CompaniesUpdate' => \App\Http\Middleware\CompaniesApp\Update::class,
        'CompaniesDelete' => \App\Http\Middleware\CompaniesApp\Update::class,

// شجره الهياكل
        'AdministrativeAll' => \App\Http\Middleware\AdministrativeStructure\All::class,
        'AdministrativeAdd' => \App\Http\Middleware\AdministrativeStructure\Add::class,
        'AdministrativeUpdate' => \App\Http\Middleware\AdministrativeStructure\Update::class,
        'AdministrativeDelete' => \App\Http\Middleware\AdministrativeStructure\Delete::class,

//اكواد الانظمه
        'SystemCodeAll' => \App\Http\Middleware\SystemCode\All::class,
        'SystemCodeAdd' => \App\Http\Middleware\SystemCode\Add::class,
        'SystemCodeUpdate' => \App\Http\Middleware\SystemCode\Update::class,
        'SystemCodeDelete' => \App\Http\Middleware\SystemCode\Delete::class,

//        بيانات الموظفين
        'EmployeeAll' => \App\Http\Middleware\Employee\All::class,
        'EmployeeAdd' => \App\Http\Middleware\Employee\Add::class,
        'EmployeeUpdate' => \App\Http\Middleware\Employee\Update::class,
        'EmployeeDelete' => \App\Http\Middleware\Employee\Delete::class,

//        اعدادات النظام
        'EmployeeVariableSettingAll' => \App\Http\Middleware\EmployeeVariableSettings\All::class,
        'EmployeeVariableSettingAdd' => \App\Http\Middleware\EmployeeVariableSettings\Add::class,
        'EmployeeVariableSettingUpdate' => \App\Http\Middleware\EmployeeVariableSettings\Update::class,


//        الاضافات والخصومات
        'MonthlyAdditionsAll' => \App\Http\Middleware\MonthlyAdditions\All::class,
        'MonthlyAdditionsAdd' => \App\Http\Middleware\MonthlyAdditions\Add::class,
        'MonthlyDeductionsAll' => \App\Http\Middleware\MonthlyDeductions\All::class,
        'MonthlyDeductionsAdd' => \App\Http\Middleware\MonthlyDeductions\Add::class,


//        الفواتير
        'InvoicesAll' => \App\Http\Middleware\Invoices\All::class,
        'InvoicesAdd' => \App\Http\Middleware\Invoices\Add::class,
        'InvoicesCargo' => \App\Http\Middleware\Invoices\Cargo::class,

//        بوليصه الشحن
        'WayBillAll' => \App\Http\Middleware\WayBill\All::class,
        'WayBillAdd' => \App\Http\Middleware\WayBill\Add::class,
        'WayBillUpdate' => \App\Http\Middleware\WayBill\Edit::class,
        'WayBillCancel' => \App\Http\Middleware\WayBill\Cancel::class,

        //        بوليصه نقل صغير
        'WayBillCargo2All' => \App\Http\Middleware\WayBillCargo2\All::class,
        'WayBillCargo2Add' => \App\Http\Middleware\WayBillCargo2\Add::class,
        'WayBillCargo2Update' => \App\Http\Middleware\WayBillCargo2\Update::class,
        'WayBillCargo2Cancel' => \App\Http\Middleware\WayBillCargo2\Cancel::class,

        ///سندات القبض
        'BondsCaptureAll' => \App\Http\Middleware\CaptureBonds\All::class,
        'BondsCaptureAdd' => \App\Http\Middleware\CaptureBonds\Add::class,

        ///سندات الصرف
        'BondsCashAll' => \App\Http\Middleware\CashBonds\All::class,
        'BondsCashAdd' => \App\Http\Middleware\CashBonds\Add::class,


        ////////////trips
        'viewTrip' => \App\Http\Middleware\Trips\All::class,
        'addTrip' => \App\Http\Middleware\Trips\Add::class,
        'editTrip' => \App\Http\Middleware\Trips\Edit::class,
        'launchTrip' => \App\Http\Middleware\Trips\Launch::class,

    ];
}
