<?php

use App\Http\Controllers\CarRent\CarRentBrandController;
use App\Models\JournalDt;
use App\Models\SystemCode;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\Naql\NaqlAPIController;
use App\Http\Controllers\Naql\NaqlWayAPIController;


use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'tracking'], function () {

    Route::get('/{id}', 'App\Http\Controllers\Tracking\WaybillCar\trackingController@show');

});

Route::group(['prefix' => 'claim'], function () {

    Route::get('/{id}', 'App\Http\Controllers\Tracking\WaybillCar\trackingController@claim');

});


Route::get('/add-way', function () {
    $company = session('company') ? session('company') : auth()->user()->company;
    $invoices_ids = \App\Models\WaybillHd::where('waybill_type_id', 1)
        ->where('company_group_id', $company->company_group_id)
        ->whereHas('invoice')->pluck('waybill_invoice_id')->toArray();

    $journals_ids = \App\Models\InvoiceHd::whereIn('invoice_id', $invoices_ids)->pluck('journal_hd_id')
        ->toArray();

    $journals = \App\Models\JournalHd::has('journalDetails', '<', 4)
        ->whereIn('journal_hd_id', $journals_ids)->with('journalDetails')->get()->take(10);

    foreach ($journals as $journal) {
        if (isset($journal->invoice->invoiceDetails[1])) {
            $sum1 = $journal->journalDetails[0]->journal_dt_debit;
            $sum2 = $journal->journalDetails[1]->journal_dt_credit + $journal->journalDetails[2]->journal_dt_credit;

            if (ceil($sum1) != ceil($sum2)) {
                $item = SystemCode::where('system_code', 541)
                    ->where('company_group_id', $company->company_group_id)->first();

                $sales_notes = $item->account->acc_name_ar . ' ' . 'فاتوره المبيعات رقم' . ' ' . $journal->invoice->invoice_no;
                $journal_type = SystemCode::where('system_code', 808)
                    ->where('company_group_id', $company->company_group_id)->first();

                $journal_status = SystemCode::where('system_code', 903)
                    ->where('company_group_id', $company->company_group_id)->first();

                $cost_center_type_id_branch = SystemCode::where('system_code', 56005)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;


                $item_amount = $journal->invoice->invoiceDetails[1]
                    ->invoice_item_amount;

                JournalDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $item->system_code_acc_id,
                    //'account_id' => 62,
                    'journal_dt_debit' => 0,
                    'journal_dt_credit' => $item_amount,
                    'journal_dt_balance' => $item_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'journal_dt_notes' => $sales_notes,
                    'cc_branch_id' => session('branch')['branch_id'],
                    'cost_center_type_id' => $cost_center_type_id_branch,
                    'cost_center_id' => 73,
                    'cc_voucher_id' => $journal->invoice->invoice_id,
                ]);
            }
            dump($journal->journal_hd_code);
        }
    }

});
/*
* Application QR CODE...
*/
//Route::get('qr-code-g', function () {
//
//    \QrCode::size(500)
//            ->format('png')
//            ->generate('perfect.co', public_path('images/qrcode.png'));
//
//  return view('qrCode');
//
//});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/short/{shortURLKey}', '\AshAllenDesign\ShortURL\Controllers\ShortURLController');

Route::get('/test-date', function () {

//   return \App\Models\GL\GlDetail::select('gl_detail_id','balance', 'credit', 'debit','notes')
//        ->where('gl_header_id', 207)->orderBy('gl_detail_id','asc')->get();

//    return view('CarRent.Contract.img');
    // return view('CarRent.Contract.create1');

    $hijri_date = Carbon::parse(request()->date);
    $year = $hijri_date->format('Y');
    $month = $hijri_date->format('m');
    $day = $hijri_date->format('d');
//
/////    return floor((11 * $year + 3) / 30) + floor(354 * $year) + floor(30 * $month)
    ////   - floor(($month - 1) / 2) + $day + 1948440 - 386;


    $date = (int)((11 * $year + 3) / 30) + 354 * $year +
        30 * $month - (int)(($month - 1) / 2) + $day + 1948440 - 385;

    $new_date = Carbon::parse(jdtogregorian($date))->format('Y-m-d');
    return response()->json(['data' => $new_date]);

})->name('test-date');


Route::get('/test-waybills', function () {
    $waybills = \App\Models\WaybillHd::where('company_group_id', 4)
        ->where('waybill_type_id', 4)->get();
    foreach ($waybills as $waybill) {
        if ($waybill->details) {
            $waybill->update(['waybills_cars_count' => (int)$waybill->details->waybill_qut_received_customer]);
        }

    }
});


//Route::get('test', function () {
//   $invoices = InvoiceHd::get();
//    foreach ($invoices as $invoice_hd) {
//        $qr = QRDataGenerator::fromArray([
//            new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
//            new TaxNoElement($invoice_hd->companyGroup->tax_number),
//           new InvoiceDateElement(Carbon::parse($invoice_hd->invoice_date)->toIso8601ZuluString()),
//           new TotalAmountElement($invoice_hd->invoice_amount),
//           new TaxAmountElement($invoice_hd->invoice_vat_amount)
//      ])->toBase64();
//       $invoice_hd->update(['qr_data' => $qr]);
//   }
//   return $qr;
//});

////////////////////////////////// Reports


Route::get('/report-rent', 'App\Http\Controllers\RepotsController@indexaccount')->middleware('auth')->name('rent-reports');
Route::get('/report-mntns', 'App\Http\Controllers\RepotsController@indexmntns')->middleware('auth')->name('mntns-reports');
Route::get('/report-store', 'App\Http\Controllers\RepotsController@indexstore')->middleware('auth')->name('store-reports');
Route::get('/report-salescar', 'App\Http\Controllers\RepotsController@indexsalescar')->middleware('auth')->name('sales-car-reports');
Route::get('/report-waybill', 'App\Http\Controllers\RepotsController@indexwaybill')->middleware('auth')->name('waybill-reports');
Route::get('/report-bond', 'App\Http\Controllers\RepotsController@indexbond')->middleware('auth')->name('bond-reports');
Route::get('/report-employee', 'App\Http\Controllers\RepotsController@indexemployee')->middleware('auth')->name('employee-reports');

///////////////////////////////////////////////////////////////////////////


Route::get('/export-way-bill', 'App\Http\Controllers\WaybillController@export')->middleware('auth')->name('waybill-export');

Route::get('/export-way-bill-car', 'App\Http\Controllers\WaybillCarController@export')->middleware('auth')->name('waybill-car-export');

Route::get('/export-way-bill-cargo2', 'App\Http\Controllers\WaybillCargo2Controller@export')->middleware('auth')->name('waybill-cargo2-export');

Route::get('/export-invoices', 'App\Http\Controllers\InvoicesController@export')->middleware('auth')->name('invoices-export');

Route::get('/exportcredit-invoices', 'App\Http\Controllers\InvoicesController@exportcredit')->middleware('auth')->name('invoices-exportcredit');

Route::get('/exportacc-invoices', 'App\Http\Controllers\InvoicesController@exportacc')->middleware('auth')->name('invoices-exportacc');

Route::get('/exportdebit-invoices', 'App\Http\Controllers\InvoicesController@exportdebit')->middleware('auth')->name('invoices-exportdebit');


Route::get('/export-invoices-returns', 'App\Http\Controllers\InvoicesController@exportreturn')->middleware('auth')->name('invoices-exportreturn');
///////////////////////////////////////////////////////////////////////////////////
Route::get('/generate-qrcode', [QrCodeController::class, 'index']);

//////////////////////////////////////////////////////////////////////////////

Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');


Route::get('/sales', 'App\Http\Controllers\HomeController@sales')->name('sales')->middleware('auth');
Route::get('/store', 'App\Http\Controllers\HomeController@store')->name('store')->middleware('auth');

Route::get('/main', 'App\Http\Controllers\HomeController@main')->name('main.dashboard')->middleware('auth');
Route::get('/main1', 'App\Http\Controllers\HomeController@main1')->name('main1.dashboard')->middleware('auth');
Route::get('/main2', 'App\Http\Controllers\HomeController@main2')->name('main2.dashboard')->middleware('auth');
Route::get('/main3', 'App\Http\Controllers\HomeController@main3')->name('main3.dashboard')->middleware('auth');
Route::get('/main4', 'App\Http\Controllers\HomeController@main4')->name('main4.dashboard')->middleware('auth');
Route::get('/main5', 'App\Http\Controllers\HomeController@main5')->name('main5.dashboard')->middleware('auth');
Route::get('/main6', 'App\Http\Controllers\HomeController@main6')->name('main6.dashboard')->middleware('auth');
Route::get('/main7', 'App\Http\Controllers\HomeController@main7')->name('main7.dashboard')->middleware('auth');

//accounts
Route::group(['prefix' => 'account-tree', 'namespace' => 'App\Http\Controllers', 'middleware' => 'auth'], function () {

    Route::get('/', 'AccountTreeController@index')->name('accountTree');

    Route::get('/indexMain', 'AccountController@indexMain')->name('accounts.indexMain');

    Route::get('/periods', 'AccountController@indexperiod')->name('accounts.periods');

    Route::get('{id}/storeperiod', 'AccountController@storeperiod')->name('accounts.storeperiod');

    Route::get('/create', 'AccountController@create')->name('accounts.create');

    Route::post('/store', 'AccountController@store')->name('accounts.store');

    Route::get('{id}/edit', 'AccountController@edit')->name('accounts.edit');

    Route::put('{id}/update', 'AccountController@update')->name('accounts.update');

    Route::delete('{id}/delete', 'AccountController@delete')->name('accounts.delete');

    Route::group(['prefix' => 'Api'], function () {

        Route::get('/get-parents', 'AccountController@getAccountParents')->name('api.accounts.getParents');

        Route::get('/get-serial', 'AccountController@getSerial')->name('api.accounts.getSerial');

    });


});


Route::get('/locale/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back();
})->name('locale');

Route::post('/logout', 'App\Http\Controllers\LoginController@logout')->name('logout')->middleware('auth');

Route::group(['prefix' => 'login'], function () {

    Route::get('/company-group', 'App\Http\Controllers\LoginController@getCompanyGroupWithCompanies')->name('get-user-mainCompany-Companies');

    Route::get('/company-branches', 'App\Http\Controllers\LoginController@getCompanyBranches')->name('get-company-branches');

    Route::get('/', 'App\Http\Controllers\LoginController@index')->name('login');

    Route::post('/store', 'App\Http\Controllers\LoginController@login')->name('login.store');

});


Route::group(['prefix' => 'applications', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\ApplicationsController@index')->name('applications');

    Route::post('/store', 'App\Http\Controllers\ApplicationsController@store')->name('application.store');

    Route::get('{id}/show', 'App\Http\Controllers\ApplicationsController@show');

    Route::put('{id}/update', 'App\Http\Controllers\ApplicationsController@update')->name('application.update');

});

Route::group(['prefix' => 'application-menu', 'middleware' => 'auth'], function () {

    Route::get('/{id}', 'App\Http\Controllers\ApplicationMenuController@index')->name('applicationMenu.index');

    Route::get('{application_id}/create', 'App\Http\Controllers\ApplicationMenuController@create')->name('applicationMenu.create');

    Route::post('/store', 'App\Http\Controllers\ApplicationMenuController@store')->name('applicationMenu.store');

    Route::get('/{id}/show', 'App\Http\Controllers\ApplicationMenuController@show')->name('applicationMenu.show');

    Route::put('/{id}/update', 'App\Http\Controllers\ApplicationMenuController@update')->name('applicationMenu.update');

});

Route::group(['prefix' => 'company-group', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\CompanyGroupController@index')->name('mainCompanies');

    Route::get('/create', 'App\Http\Controllers\CompanyGroupController@create')->name('mainCompanies.create');

    Route::post('/store', 'App\Http\Controllers\CompanyGroupController@store')->name('mainCompanies.store');

    Route::get('/{id}/show', 'App\Http\Controllers\CompanyGroupController@show');

    Route::post('/{id}/update', 'App\Http\Controllers\CompanyGroupController@update')->name('mainCompanies.update');

});

Route::group(['prefix' => 'company', 'middleware' => 'auth'], function () {

    Route::get('{company_group}/create', 'App\Http\Controllers\CompanyController@create')->name('company.create');

    Route::post('/store', 'App\Http\Controllers\CompanyController@store')->name('company.store');

    Route::get('/{id}/edit', 'App\Http\Controllers\CompanyController@edit')->name('company.edit');

    Route::post('/{id}/update', 'App\Http\Controllers\CompanyController@update')->name('company.update');

});

Route::group(['prefix' => 'company-app', 'middleware' => 'auth'], function () {

    Route::post('/store', 'App\Http\Controllers\CompanyAppController@store')->name('company-app.store');

    Route::put('/{id}/update', 'App\Http\Controllers\CompanyAppController@update')->name('company-app.update');

    Route::delete('/{id}/delete', 'App\Http\Controllers\CompanyAppController@delete')->name('company-app.delete');

});

Route::group(['prefix' => 'branch', 'middleware' => 'auth'], function () {

    Route::get('{id}/location', 'App\Http\Controllers\BranchController@getLocation')->name('company-branch-location');

    Route::post('/store', 'App\Http\Controllers\BranchController@store')->name('company-branches.store');

    Route::put('/{id}/update', 'App\Http\Controllers\BranchController@update')->name('company-branch.update');

});

Route::group(['prefix' => 'users-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\UserController@index')->name('users');

    Route::get('/{id}/edit', 'App\Http\Controllers\UserController@edit')->name('user.edit');

    Route::get('/{id}/edit/profile', 'App\Http\Controllers\UserController@edit')->name('user.edit.profile');

    Route::post('/store', 'App\Http\Controllers\UserController@store')->name('user.store');

    Route::put('/{id}/update', 'App\Http\Controllers\UserController@update')->name('user.update');

    Route::get('/get-company-list', 'App\Http\Controllers\UserController@getCompanyList');

});

Route::group(['prefix' => 'company-administrative-structure', 'middleware' => 'auth'], function () {

    Route::get('/departments', 'App\Http\Controllers\CompanyAdministrativeStructuresController@getCompanyDetails')->name('administrativeStructures');

});

Route::group(['prefix' => 'company-departments', 'middleware' => 'auth'], function () {

    Route::get('/get-companies', 'App\Http\Controllers\DepartmentController@getCompanies');

    Route::post('/store', 'App\Http\Controllers\DepartmentController@store')->name('department.store');

    Route::get('{id}/edit', 'App\Http\Controllers\DepartmentController@edit')->name('department.edit');

    Route::put('/{id}/update', 'App\Http\Controllers\DepartmentController@update')->name('department.update');

    Route::delete('/{id}/delete', 'App\Http\Controllers\DepartmentController@delete')->name('department.delete');

});

Route::group(['prefix' => 'user-branches', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\UserBranchesController@index');

    Route::post('/{id}/create', 'App\Http\Controllers\UserBranchesController@create')->name('user.branches.create');

    Route::post('/store', 'App\Http\Controllers\UserBranchesController@store')->name('user.branches.store');

    Route::get('/{user}/{branch}/edit', 'App\Http\Controllers\UserBranchesController@edit')->name('user.branches.edit');

    Route::put('/{id}/update', 'App\Http\Controllers\UserBranchesController@update')->name('user.branches.update');

    Route::get('/{id}/showJob', 'App\Http\Controllers\UserBranchesController@showJob');

});

Route::group(['prefix' => 'company-divisions', 'middleware' => 'auth'], function () {

    Route::get('/get-companies-store', 'App\Http\Controllers\DivisionController@getCompaniesForStore')->name('department.get-companies-store');

    Route::get('/get-companies-update', 'App\Http\Controllers\DivisionController@getCompaniesForUpdate');

    Route::post('/store', 'App\Http\Controllers\DivisionController@store')->name('division.store');

    Route::get('/{id}/edit', 'App\Http\Controllers\DivisionController@edit')->name('division.edit');

    Route::put('/{id}/update', 'App\Http\Controllers\DivisionController@update')->name('division.update');

    Route::delete('/{id}/delete', 'App\Http\Controllers\DivisionController@delete')->name('division.delete');

});

Route::group(['prefix' => 'company-jobs', 'middleware' => 'auth'], function () {

    Route::get('/get-companies-store', 'App\Http\Controllers\JobController@getCompaniesForStore')->name('divisions.get-companies-store');

    Route::get('/get-companies-update', 'App\Http\Controllers\JobController@getCompaniesForUpdate');

    Route::post('/store', 'App\Http\Controllers\JobController@store')->name('job.store');

    Route::get('/{id}/edit', 'App\Http\Controllers\JobController@edit')->name('job.edit');

    Route::post('/{id}/update', 'App\Http\Controllers\JobController@update')->name('job.update');

    Route::delete('/{id}/delete', 'App\Http\Controllers\JobController@delete')->name('job.delete');

});

Route::group(['prefix' => 'job-permissions', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\PermissionController@index')->name('jobPermissions');

    Route::get('/{id}/show', 'App\Http\Controllers\PermissionController@show')->name('jobPermissions.show');

    Route::get('/get-company-apps', 'App\Http\Controllers\PermissionController@getCompanyApplications')->name('get-company-apps');

    Route::get('/get-applications-menu', 'App\Http\Controllers\PermissionController@getApplicationsMenu')->name('get-applications-menu');

    Route::get('/get-company-app-menu-remain', 'App\Http\Controllers\PermissionController@getRemainApplicationMenu')->name('get-company-app-menu-remain');

    Route::post('/store', 'App\Http\Controllers\PermissionController@store')->name('job-permissions.store');

    Route::put('/{id}/update', 'App\Http\Controllers\PermissionController@update')->name('job-permissions.update');


    Route::delete('/{id}/delete', 'App\Http\Controllers\PermissionController@delete')->name('job-permission.delete');

});

Route::group(['prefix' => 'Products', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\SystemCodeCategoryController@index2')->name('Products');

    Route::post('/store', 'App\Http\Controllers\SystemCodeCategoryController@store2')->name('Products.store');

    Route::put('/{id}/update', 'App\Http\Controllers\SystemCodeCategoryController@update2')->name('Products.update');

    Route::delete('/{id}/delete', 'App\Http\Controllers\SystemCodeCategoryController@delete2')->name('Products.delete');

});

Route::group(['prefix' => 'system-code-categories', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\SystemCodeCategoryController@index')->name('systemCodeCategories');

    Route::get('/locations', 'App\Http\Controllers\SystemCodeCategoryController@indexloc')->name('systemCodelocations');

    Route::post('/store', 'App\Http\Controllers\SystemCodeCategoryController@store')->name('systemCodeCategories.store');

    Route::put('/{id}/update', 'App\Http\Controllers\SystemCodeCategoryController@update')->name('systemCodeCategories.update');

    Route::delete('/{id}/delete', 'App\Http\Controllers\SystemCodeCategoryController@delete');

});

Route::group(['prefix' => 'system-codes', 'middleware' => 'auth'], function () {

    Route::get('/get-company-groups', 'App\Http\Controllers\SystemCodeController@getCompanyGroup');

    Route::get('/get-company', 'App\Http\Controllers\SystemCodeController@getCompany');

    Route::post('/store', 'App\Http\Controllers\SystemCodeController@store')->name('system-codes.store');

    Route::get('{id}/edit', 'App\Http\Controllers\SystemCodeController@edit')->name('system-codes.show');

    Route::put('{id}/update', 'App\Http\Controllers\SystemCodeController@update')->name('system-codes.update');

    Route::delete('{id}/delete', 'App\Http\Controllers\SystemCodeController@delete');

});

Route::group(['prefix' => 'attachments', 'middleware' => 'auth'], function () {

    Route::post('/store', 'App\Http\Controllers\Files\FilesController@store')->name('attachments.store');
    Route::get('{id}/edit', 'App\Http\Controllers\Files\FilesController@edit')->name('attachments.edit');
    Route::put('{id}/update', 'App\Http\Controllers\Files\FilesController@update')->name('attachments.update');
    Route::get('/download-pdf', 'App\Http\Controllers\Files\FilesController@downloadPdf')->name('attachments.download');

});

Route::group(['prefix' => 'notes', 'middleware' => 'auth'], function () {

    Route::post('/store', 'App\Http\Controllers\Files\NotesController@store')->name('notes.store');

});

Route::group(['prefix' => 'employees-add', 'middleware' => 'auth'], function () {

    Route::get('/dashboard', 'App\Http\Controllers\EmployeeDashboardController@index')->name('employees.dashboard');

    Route::get('/', 'App\Http\Controllers\EmployeeController@index')->name('employees');

    Route::get('/export', 'App\Http\Controllers\EmployeeController@export')->name('employees.export');

    Route::get('/create', 'App\Http\Controllers\EmployeeController@create')->name('employees.create');

    Route::post('/store', 'App\Http\Controllers\EmployeeController@store')->name('employees.store');

    Route::get('{id}/edit', 'App\Http\Controllers\EmployeeController@edit')->name('employees.edit');

    Route::put('{id}/update', 'App\Http\Controllers\EmployeeController@update')->name('employees.update');

    Route::delete('{id}/deleteEmployee', 'App\Http\Controllers\EmployeeController@deleteEmployee')->name('employees.delete');

    Route::delete('/{id}/delete', 'App\Http\Controllers\EmployeeController@delete')->name('employees-attachment.delete');

});

Route::group(['prefix' => 'employees-contracts', 'middleware' => 'auth'], function () {

    Route::get('{id}/create', 'App\Http\Controllers\EmployeeContractController@create')->name('employees-contracts-create');

    Route::post('/store', 'App\Http\Controllers\EmployeeContractController@store')->name('employees-contracts-store');

    Route::get('{id}/edit', 'App\Http\Controllers\EmployeeContractController@edit')->name('employees-contracts-edit');

    Route::put('{id}/update', 'App\Http\Controllers\EmployeeContractController@update')->name('employees-contracts-update');
});

Route::group(['prefix' => 'employees-contracts-salary', 'middleware' => 'auth'], function () {

    Route::post('/store', 'App\Http\Controllers\EmployeeContractSalaryController@store')->name('employee-contract-salary');

});

Route::group(['prefix' => 'employees-variable-settings', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\EmployeeVariableSetting@index')->name('employees-variables-setting.add_ons');

    Route::post('/storeAdd_ons', 'App\Http\Controllers\EmployeeVariableSetting@storeAdd_ons')->name('employees-variables-setting.storeAdd_ons');

    Route::get('/{id}/editAdd_ons', 'App\Http\Controllers\EmployeeVariableSetting@editAdd_ons')->name('employees-variables-setting.editAdd_ons');

    Route::put('/{id}/updateAdd_ons', 'App\Http\Controllers\EmployeeVariableSetting@updateAdd_ons')->name('employees-variables-setting.updateAdd_ons');

    Route::post('/storeDiscounts', 'App\Http\Controllers\EmployeeVariableSetting@storeDiscounts')->name('employees-variables-setting.storeDiscount');

    Route::get('/{id}/editADiscount', 'App\Http\Controllers\EmployeeVariableSetting@editDiscount')->name('employees-variables-setting.editDiscount');

    Route::put('/{id}/updateDiscounts', 'App\Http\Controllers\EmployeeVariableSetting@updateDiscount')->name('employees-variables-setting.updateDiscount');

    Route::post('/storeSalariesAccount', 'App\Http\Controllers\EmployeeVariableSetting@storeSalariesAccount')->name('employees-variables-setting.storeSalariesAccount');

    Route::post('/storeSalaryTypesAccounts', 'App\Http\Controllers\EmployeeVariableSetting@storeSalaryTypesAccounts')->name('employees-variables-setting.storeSalaryTypesAccounts');

});

Route::group(['prefix' => 'monthly-additions', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\MonthlyAdditionsController@index')->name('monthly-additions');

    Route::get('/create', 'App\Http\Controllers\MonthlyAdditionsController@create')->name('monthly-additions.create');

    Route::post('/store', 'App\Http\Controllers\MonthlyAdditionsController@store')->name('monthly-additions.store');

    Route::put('/{id}/update', 'App\Http\Controllers\MonthlyAdditionsController@update')->name('monthly-additions.update');

    Route::get('/{id}/edit', 'App\Http\Controllers\MonthlyAdditionsController@edit')->name('monthly-additions.edit');

});

Route::group(['prefix' => 'monthly-deductions', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\MonthlyDeductionsController@index')->name('monthly-deductions');

    Route::get('/create', 'App\Http\Controllers\MonthlyDeductionsController@create')->name('monthly-deductions.create');

    Route::post('/store', 'App\Http\Controllers\MonthlyDeductionsController@store')->name('monthly-deductions.store');

    Route::put('/{id}/update', 'App\Http\Controllers\MonthlyDeductionsController@update')->name('monthly-deductions.update');

    Route::get('/{id}/edit', 'App\Http\Controllers\MonthlyDeductionsController@edit')->name('monthly-deductions.edit');
});


//////////////////suppliers

Route::group(['prefix' => 'suppliers-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\SupplierController@index')->name('suppliers');
    Route::get('/create', 'App\Http\Controllers\SupplierController@create')->name('Suppliers.create');
    Route::get('/{id}/edit', 'App\Http\Controllers\SupplierController@edit')->name('Suppliers.edit');
    Route::post('/store', 'App\Http\Controllers\SupplierController@store')->name('Suppliers.store');
    Route::put('{id}/update', 'App\Http\Controllers\SupplierController@update')->name('Suppliers.update');


});

//////////////////customers

Route::group(['prefix' => 'customers-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\CustomerController@index')->name('customers');
    Route::get('/create', 'App\Http\Controllers\CustomerController@create')->name('Customers.create');
    Route::get('/{id}/edit', 'App\Http\Controllers\CustomerController@edit')->name('Customers.edit');
    Route::post('/store', 'App\Http\Controllers\CustomerController@store')->name('Customers.store');
    Route::put('{id}/update', 'App\Http\Controllers\CustomerController@update')->name('Customers.update');


});

//////////////////PriceList

Route::group(['prefix' => 'PriceList-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\PriceListControllers@index')->name('PriceList');
    Route::get('/create', 'App\Http\Controllers\PriceListControllers@create')->name('PriceList.create');
    Route::get('/{id}/edit', 'App\Http\Controllers\PriceListControllers@edit')->name('PriceList.edit');
    Route::get('/{id}/duplicate', 'App\Http\Controllers\PriceListControllers@showDuplicate')->name('PriceList.duplicate');
    Route::post('/store', 'App\Http\Controllers\PriceListControllers@store')->name('PriceList.store');
    Route::put('{id}/update', 'App\Http\Controllers\PriceListControllers@update')->name('PriceList.update');
    Route::delete('/delete', 'App\Http\Controllers\PriceListControllers@delete')->name('PriceList.delete');


});

//////////////////PriceList-cargo

Route::group(['prefix' => 'PriceList-cargo-add', 'middleware' => 'auth'], function () {

    Route::get('/P-cargo', 'App\Http\Controllers\PriceListControllers@index_cargo')->name('PriceList-cargo');
    Route::get('/P-cargo-create', 'App\Http\Controllers\PriceListControllers@create_cargo')->name('PriceList-cargo.create');
    Route::get('/{id}/P-cargo-edit', 'App\Http\Controllers\PriceListControllers@edit_cargo')->name('PriceList-cargo.edit');
    Route::post('/P-cargo-store', 'App\Http\Controllers\PriceListControllers@store_cargo')->name('PriceList-cargo.store');
    Route::put('{id}/P-cargo-update', 'App\Http\Controllers\PriceListControllers@update_cargo')->name('PriceList-cargo.update');
    Route::delete('/P-cargo-delete', 'App\Http\Controllers\PriceListControllers@delete_cargo')->name('PriceList-cargo.delete');


});


//////////////////PriceList-int

Route::group(['prefix' => 'PriceList-int-add', 'middleware' => 'auth'], function () {

    Route::get('/P-int', 'App\Http\Controllers\PriceListControllers@index_int')->name('PriceList-int');
    Route::get('/P-int-create', 'App\Http\Controllers\PriceListControllers@create_int')->name('PriceList-int.create');
    Route::get('/{id}/P-int-edit', 'App\Http\Controllers\PriceListControllers@edit_int')->name('PriceList-int.edit');
    Route::post('/P-int-store', 'App\Http\Controllers\PriceListControllers@store_int')->name('PriceList-int.store');
    Route::put('{id}/P-int-update', 'App\Http\Controllers\PriceListControllers@update_int')->name('PriceList-int.update');
    Route::delete('/P-int-delete', 'App\Http\Controllers\PriceListControllers@delete_int')->name('PriceList-int.delete');


});

//////////////////trucks

Route::group(['prefix' => 'trucks-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\TrucksController@index')->name('Trucks');

    Route::get('/create', 'App\Http\Controllers\TrucksController@create')->name('Trucks.create');

    Route::get('/{id}/edit', 'App\Http\Controllers\TrucksController@edit')->name('Trucks.edit');

    Route::post('/store', 'App\Http\Controllers\TrucksController@store')->name('Trucks.store');

    Route::put('{id}/update', 'App\Http\Controllers\TrucksController@update')->name('Trucks.update');

});

///////////////////المقطورات
Route::group(['prefix' => 'trailers-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\TrailersController@index')->name('Trailers');

    Route::get('/create', 'App\Http\Controllers\TrailersController@create')->name('Trailers.create');

    Route::post('/store', 'App\Http\Controllers\TrailersController@store')->name('Trailers.store');

    Route::get('{id}/edit', 'App\Http\Controllers\TrailersController@edit')->name('Trailers.edit');

    Route::put('{id}/update', 'App\Http\Controllers\TrailersController@update')->name('Trailers.update');

});


////////////////ربط الشاحنات
Route::group(['prefix' => 'trucks-trailers-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\TruckTrailersConnectController@index')->name('TrucksTrailers');

    Route::get('/create', 'App\Http\Controllers\TruckTrailersConnectController@create')->name('TrucksTrailers.create');

    Route::get('{id}/show', 'App\Http\Controllers\TruckTrailersConnectController@show')->name('TrucksTrailers.show');

    Route::post('/store', 'App\Http\Controllers\TruckTrailersConnectController@store')->name('TrucksTrailers.store');

    Route::get('/getTruckDetails', 'App\Http\Controllers\TruckTrailersConnectController@getTruckDetails')->name('TrucksTrailers.getTruckDetails');

    Route::get('/disConnectTruck', 'App\Http\Controllers\TruckTrailersConnectController@disConnectTruck')->name('TrucksTrailers.disConnectTruck');

});


//////////////////tripline

Route::group(['prefix' => 'TripLine-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\TripLineControllers@index')->name('TripLine');

    Route::get('/create', 'App\Http\Controllers\TripLineControllers@create')->name('TripLine.create');

    Route::get('/{id}/edit', 'App\Http\Controllers\TripLineControllers@edit')->name('TripLine.edit');

    Route::post('/store', 'App\Http\Controllers\TripLineControllers@store')->name('TripLine.store');

    Route::put('{id}/update', 'App\Http\Controllers\TripLineControllers@update')->name('TripLine.update');

    Route::delete('/delete', 'App\Http\Controllers\TripLineControllers@delete')->name('TripLine.delete');

});


//////////////////trip

Route::group(['prefix' => 'Trips-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\TripControllers@index')->name('Trips')->middleware('viewTrip');

    Route::get('/create', 'App\Http\Controllers\TripControllers@create')->name('Trips.create')->middleware('addTrip');

    Route::get('/{id}/create-trip', 'App\Http\Controllers\TripControllers@create2')->name('Trips.create2')->middleware('addTrip');

    Route::get('/{id}/edit', 'App\Http\Controllers\TripControllers@edit')->name('Trips.edit')->middleware('editTrip');

    Route::post('/store', 'App\Http\Controllers\TripControllers@store')->name('Trips.store')->middleware('addTrip');

    Route::post('/storeDt', 'App\Http\Controllers\TripControllers@storeDt')->name('Trips.storeDetails')->middleware('addTrip');

    Route::post('/updateStatus', 'App\Http\Controllers\TripControllers@updateStatus')->name('Trips.updateStatus')->middleware('editTrip');

    Route::post('/confirmUpdate', 'App\Http\Controllers\TripControllers@confirmUpdate')->name('Trips.confirmUpdate')->middleware('editTrip');

    Route::put('{id}/update', 'App\Http\Controllers\TripControllers@update')->name('Trips.update')->middleware('editTrip');


    //////////   Apis

    Route::post('/createTrip', 'App\Http\Controllers\TripControllers@createTrip')->name('api.Trips.createTrip');

    Route::get('/getOldTripData', 'App\Http\Controllers\TripControllers@getOldTripData')->name('api.Trips.getOldTripData');

    Route::get('/getOldTripLines', 'App\Http\Controllers\TripControllers@getOldTripLines')->name('api.Trips.getOldTripLines');

    Route::get('/printTrip', 'App\Http\Controllers\TripControllers@printTrip')->name('api.Trips.printTrip');

    Route::post('/createWaybillTrip', 'App\Http\Controllers\WaybillController@createTrip')->name('api.Waybill.createTrip');

    Route::put('/cancelWaybill', 'App\Http\Controllers\WaybillController@cancelWaybill')->name('api.Waybill.cancelWaybill');

    Route::get('/printWaybill', 'App\Http\Controllers\WaybillController@printWaybill')->name('api.Waybill.printWaybill');

    Route::get('/getTruck', 'App\Http\Controllers\TripControllers@getTruck')->name('api.Trips.getTruck');

    Route::get('/getTripLine', 'App\Http\Controllers\TripControllers@getTripLine')->name('api.Trips.getTripLine');

    Route::get('/getTripLines', 'App\Http\Controllers\TripControllers@getTripLines')->name('api.Trips.getTripLines');

    Route::get('/getArrivalDate', 'App\Http\Controllers\TripControllers@getArrivalDate')->name('api.Trips.getArrivalDate');

    Route::get('/getWaybillHd', 'App\Http\Controllers\TripControllers@getWaybillHd')->name('api.Trips.getWaybillHd');

    Route::get('/getWaybillData', 'App\Http\Controllers\TripControllers@getWaybillData')->name('api.Trips.getWaybillData');
    Route::get('/getWaybillDataOld', 'App\Http\Controllers\TripControllers@getWaybillDataOld')->name('api.Trips.getWaybillDataOld');

    Route::get('/getTripDts', 'App\Http\Controllers\TripControllers@getTripDts')->name('api.Trips.getTripDts');

    Route::delete('/deleteTripDt', 'App\Http\Controllers\TripControllers@deleteTripDt')->name('api.Trips.deleteTripDt');


});


//////////////////invoices

Route::group(['prefix' => 'invoices-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\InvoicesController@index')->name('invoices');

    Route::get('{id}/show', 'App\Http\Controllers\InvoicesController@show')->name('invoices.show');

    Route::get('/create', 'App\Http\Controllers\InvoicesController@create')->name('Invoices.create');

    Route::post('/store', 'App\Http\Controllers\InvoicesController@store')->name('Invoices.store');

});


Route::group(['prefix' => 'invoices-purchase', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\PurchaseInvoiceController@index')->name('invoices-purchase');

    Route::get('/create', 'App\Http\Controllers\PurchaseInvoiceController@create')->name('invoices-purchase.create');

    Route::post('/store', 'App\Http\Controllers\PurchaseInvoiceController@store')->name('invoices-purchase.store');

    Route::get('{id}/showpurchase', 'App\Http\Controllers\PurchaseInvoiceController@show')->name('invoices-purchase.show');

    Route::get('{id}/edit', 'App\Http\Controllers\PurchaseInvoiceController@edit')->name('invoices-purchase.edit');

    Route::put('{id}/update', 'App\Http\Controllers\PurchaseInvoiceController@update')->name('invoices-purchase.update');

});


//////////////////invoices-acc

Route::group(['prefix' => 'invoices-acc-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\InvoicesController@indexacc')->name('invoices-acc');

    Route::get('{id}/showacc', 'App\Http\Controllers\InvoicesController@showacc')->name('invoices-acc.show');

    Route::get('/createacc', 'App\Http\Controllers\InvoicesController@createacc')->name('Invoices-acc.create');

    Route::post('/storeacc', 'App\Http\Controllers\InvoicesController@storeacc')->name('Invoices-acc.store');

    Route::post('/updateAccountsForInvoiceItems', 'App\Http\Controllers\InvoicesController@updateAccountsForInvoiceItems')
        ->name('Invoices-acc.updateAccountsForInvoiceItems');

    Route::post('{id}/addInvoiceAccJournal', 'App\Http\Controllers\InvoicesController@addInvoiceAccJournal')
        ->name('Invoices-acc.addInvoiceAccJournal');

});


//////////////////invoices-rent

Route::group(['prefix' => 'invoices-rent-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\InvoicesController@indexrent')->name('invoices-rent');

    Route::get('{id}/showrent', 'App\Http\Controllers\InvoicesController@showrent')->name('invoices-rent.show');

    Route::get('/createrent', 'App\Http\Controllers\InvoicesController@createrent')->name('Invoices-rent.create');

    Route::post('/storerent', 'App\Http\Controllers\InvoicesController@storerent')->name('Invoices-rent.store');
});
//////////////////invoices-credit

Route::group(['prefix' => 'invoices-credit-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\InvoicesController@indexcredit')->name('invoices-credit');

    Route::get('{id}/showcredit', 'App\Http\Controllers\InvoicesController@showcredit')->name('invoices-credit.show');

    Route::get('/createcredit', 'App\Http\Controllers\InvoicesController@createcredit')->name('Invoices-credit.create');

    Route::post('/storecredit', 'App\Http\Controllers\InvoicesController@storecredit')->name('Invoices-credit.store');

    Route::post('{id}/addInvoiceReturnJournal', 'App\Http\Controllers\InvoicesController@addInvoiceReturnJournal')
        ->name('Invoices-credit.addInvoiceReturnJournal');

    Route::get('/getCustomerInvoices', 'App\Http\Controllers\InvoicesController@getCustomerInvoices')
        ->name('Invoices-credit.getCustomerInvoices');

});

//////////////////invoices-debit

Route::group(['prefix' => 'invoices-debit-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\InvoicesController@indexdebit')->name('invoices-debit');

    Route::get('{id}/showdebit', 'App\Http\Controllers\InvoicesController@showdebit')->name('invoices-debit.show');

    Route::get('/createdebit', 'App\Http\Controllers\InvoicesController@createdebit')->name('Invoices-debit.create');

    Route::post('/storedebit', 'App\Http\Controllers\InvoicesController@storedebit')->name('Invoices-debit.store');

});

//////////////////invoices return

Route::group(['prefix' => 'Returned-invoices-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\InvoicesController@indexreturn')->name('Returned-invoices');

    Route::get('{id}/show', 'App\Http\Controllers\InvoicesController@showreturn')->name('Returned-invoices.show');

    Route::get('/create', 'App\Http\Controllers\InvoicesController@createreturn')->name('Returned-invoices.create');

    Route::post('/store', 'App\Http\Controllers\InvoicesController@storereturn')->name('Returned-invoices.store');

});

//////////////////invoices sales

Route::group(['prefix' => 'invoices-sales-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\InvoicesController@index2')->name('invoices.sales.index');

    Route::get('{id}/show', 'App\Http\Controllers\InvoicesController@show2')->name('invoices.sales.show');

    Route::get('/create', 'App\Http\Controllers\InvoicesController@create2')->name('Invoices.sales.create');

    Route::post('/store', 'App\Http\Controllers\InvoicesController@store2')->name('Invoices.sales.store');

    Route::get('/createDs', 'App\Http\Controllers\InvoicesController@createDs')->name('Invoices.sales.createDs');
    Route::get('/create91', 'App\Http\Controllers\InvoicesController@create91')->name('Invoices.sales.create91');
    Route::get('/create95', 'App\Http\Controllers\InvoicesController@create95')->name('Invoices.sales.create95');


});

//////////////////invoices cargo

Route::group(['prefix' => 'invoices-cargo-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\InvoicesController@indexcargo')->name('invoicesCargo2');

    Route::get('{id}/show', 'App\Http\Controllers\InvoicesController@showcargo')->name('invoices.Cargo.show');

    Route::get('/create', 'App\Http\Controllers\InvoicesController@createcargo')->name('Invoices.cargo.create');

    Route::post('/store', 'App\Http\Controllers\InvoicesController@storecargo')->name('Invoices.cargo.store');

    Route::get('{id}/edit', 'App\Http\Controllers\InvoicesController@editcargo')->name('Invoices.cargo.edit');

    Route::put('{id}/update', 'App\Http\Controllers\InvoicesController@updatecargo')->name('Invoices.cargo.update');

    ///////////////////api

    Route::get('/getCustcargoWaybills', 'App\Http\Controllers\InvoicesController@getCustcargoWaybills')->name('waybills-custcargo.getWaybills');


    Route::get('/getCustomerWaybills', 'App\Http\Controllers\InvoicesController@getCustomerWaybills')->name('waybills-car.getWaybills');

    Route::get('/getCompanyAccountPeriods', 'App\Http\Controllers\InvoicesController@getCompanyAccountPeriods')->name('waybills-car.getCompanyAccountPeriods');

});

//////////////////invoices cars

Route::group(['prefix' => 'invoices-cars-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\InvoicesController@indexcars')->name('invoicesCars');

    Route::get('{id}/show', 'App\Http\Controllers\InvoicesController@showcars')->name('invoices.Cars.show');

    Route::get('/create', 'App\Http\Controllers\InvoicesController@createcars')->name('Invoices.cars.create');

    Route::post('/store', 'App\Http\Controllers\InvoicesController@storecars')->name('Invoices.cars.store');

    Route::get('{id}/edit', 'App\Http\Controllers\InvoicesController@editCars')->name('Invoices.cars.edit');

    Route::put('{id}/update', 'App\Http\Controllers\InvoicesController@updateCars')->name('Invoices.cars.update');

});

//////////////////bonds

Route::group(['prefix' => 'bonds-add', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers'], function () {

//    سند قبض
    Route::group(['prefix' => 'capture'], function () {

        Route::group(['prefix' => 'Api'], function () {

            Route::get('/getDeservedValue', 'Api\BondsCaptureController@getDeservedValue')->name('Bonds-capture.getDeservedValue');

            Route::get('/getMaintenanceCardList', 'Api\BondsCaptureController@getMaintenanceCardList')->name('Bonds-capture.getMaintenanceCardList');

            Route::get('/getMaintenanceCardDtValue', 'Api\BondsCaptureController@getMaintenanceCardDtValue')->name('Bonds-capture.getMaintenanceCardDtValue');

            Route::get('/getAccountList', 'Api\BondsCaptureController@getAccountList')->name('Bonds-capture.getAccountList');

            Route::get('/getCustomerAccount', 'Api\BondsCaptureController@getCustomerAccount')->name('Bonds-capture.getCustomerAccount');

            Route::get('/getRelatedAccount', 'Api\BondsCaptureController@getRelatedAccount')->name('Bonds-capture.getRelatedAccount');

            Route::get('/GetTripHd', 'Api\BondsCaptureController@GetTripHd')->name('Bonds-capture.GetTripHd');

            Route::get('getBondByCode', 'Api\BondsCaptureController@getBond')->name('Bonds-capture.GetBond');


        });

        Route::get('/', 'BondController@index')->name('Bonds-capture');

        Route::get('/create', 'BondController@create')->name('Bonds-capture.create');

        Route::get('/{id}/show', 'BondController@show')->name('Bonds-capture.show');

        Route::get('/{id}/edit', 'BondController@edit')->name('Bonds-capture.edit');

        Route::put('/{id}/update', 'BondController@update')->name('Bonds-capture.update');

        Route::post('/store', 'BondController@store')->name('Bonds-capture.store');

        Route::get('/export', 'BondController@export')->name('Bonds-capture.export');

    });


    Route::group(['prefix' => 'cash'], function () {

        Route::get('/', 'BondCashController@index')->name('Bonds-cash');

        Route::get('/create', 'BondCashController@create')->name('Bonds-cash.create');

        Route::get('/{id}/show', 'BondCashController@show')->name('Bonds-cash.show');

        Route::post('/store', 'BondCashController@store')->name('Bonds-cash.store');

        Route::get('{id}/edit', 'BondCashController@edit')->name('Bonds-cash.edit');

        Route::put('{id}/update', 'BondCashController@update')->name('Bonds-cash.update');

        Route::post('/approveOneBond', 'BondCashController@approveOneBond')->name('Bonds-cash.approveOneBond');

        Route::post('/approveAllBond', 'BondCashController@approveAllBond')->name('Bonds-cash.approveAllBond');

        Route::get('/export', 'BondCashController@export')->name('Bonds-cash.export');

    });

    Route::group(['prefix' => 'addition'], function () {

        Route::get('/create', 'BondAdditionController@create')->name('Bonds-addition.create');

        Route::post('/store', 'BondAdditionController@store')->name('Bonds-addition.store');

        Route::get('{id}/show', 'BondAdditionController@show')->name('Bonds-addition.show');

    });

    Route::group(['prefix' => 'discount'], function () {

        Route::get('/create', 'BondDiscountController@create')->name('Bonds-discount.create');

        Route::post('/store', 'BondDiscountController@store')->name('Bonds-discount.store');

        Route::get('{id}/show', 'BondDiscountController@show')->name('Bonds-discount.show');

    });

    Route::group(['prefix' => 'cash-detailed'], function () {

        Route::get('/', 'BondsCashDetailedController@index')->name('bonds-cash-detailed');

        Route::get('/create', 'BondsCashDetailedController@create')->name('bonds-cash-detailed.create');

        Route::post('/store', 'BondsCashDetailedController@store')->name('bonds-cash-detailed.store');

        Route::get('{id}/show', 'BondsCashDetailedController@show')->name('bonds-cash-detailed.show');

    });

    Route::group(['prefix' => 'capture-detailed'], function () {

        Route::get('/', 'BondCaptureDetailedController@index')->name('bonds-capture-detailed');

        Route::get('/create', 'BondCaptureDetailedController@create')->name('bonds-capture-detailed.create');

        Route::post('/store', 'BondCaptureDetailedController@store')->name('bonds-capture-detailed.store');

        Route::get('{id}/show', 'BondCaptureDetailedController@show')->name('bonds-capture-detailed.show');

        Route::get('/getCustomerInvoices', 'BondCaptureDetailedController@getCustomerInvoices')->name('bonds-capture-detailed.getCustomerInvoices');

        Route::get('/getInvoiceDeservedValue', 'BondCaptureDetailedController@getInvoiceDeservedValue')->name('bonds-capture-detailed.getInvoiceDeservedValue');

    });

    Route::group(['prefix' => 'cash-safe'], function () {

        Route::get('/', 'BondCashSafeController@index')->name('bonds.cash.safe');

        Route::get('/create', 'BondCashSafeController@create')->name('bonds.cash.safe.create');

        Route::post('/store', 'BondCashSafeController@store')->name('bonds.cash.safe.store');

        Route::get('{id}/show', 'BondCashSafeController@show')->name('bonds.cash.safe.show');

        Route::get('{id}/edit', 'BondCashSafeController@edit')->name('bonds.cash.safe.edit');

        Route::put('{id}/update', 'BondCashSafeController@update')->name('bonds.cash.safe.update');

        Route::post('/approveBond', 'BondCashSafeController@approveBond')->name('bonds.cash.safe.approve');

        Route::get('/export', 'BondCashSafeController@export')->name('bonds.cash.safe.export');

    });

    Route::group(['prefix' => 'capture-safe'], function () {

        Route::get('/', 'BondCaptureSafeController@index')->name('bonds.capture.safe');

        Route::get('/create', 'BondCaptureSafeController@create')->name('bonds.capture.safe.create');

        Route::post('/store', 'BondCaptureSafeController@store')->name('bonds.capture.safe.store');

        Route::get('{id}/edit', 'BondCaptureSafeController@edit')->name('bonds.capture.safe.edit');

        Route::put('{id}/update', 'BondCaptureSafeController@update')->name('bonds.capture.safe.update');

        Route::get('{id}/show', 'BondCaptureSafeController@show')->name('bonds.capture.safe.show');

        Route::get('/export', 'BondCaptureSafeController@show')->name('bonds.capture.safe.export');

    });


});


//////////////////waybill

Route::group(['prefix' => 'Waybill-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\WaybillController@index')->name('Waybills');

    Route::get('/create', 'App\Http\Controllers\WaybillController@create')->name('Waybill.create');

    Route::post('/store', 'App\Http\Controllers\WaybillController@store')->name('Waybill.store');

    Route::get('/{id}/edit', 'App\Http\Controllers\WaybillController@edit')->name('Waybill.edit');

    Route::put('{id}/update', 'App\Http\Controllers\WaybillController@update')->name('Waybill.update');

    Route::get('/getPriceListd', 'App\Http\Controllers\Api\WaybillCarController@getPricedesile')->name('car-getPricedesile');

    Route::get('/getPhoneNumber', 'App\Http\Controllers\WaybillController@getPhoneNumber')->name('Waybill.getPhoneNumber');


});

//////////////////waybill _ 2

Route::group(['prefix' => 'Waybillcargo2-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\WaybillCargo2Controller@index')->name('WaybillsCargo2');

    Route::get('/create', 'App\Http\Controllers\WaybillCargo2Controller@create')->name('Waybillcargo2.create');

    Route::get('/createrent', 'App\Http\Controllers\WaybillCargo2Controller@createrent')->name('Waybillcargo2.createrent');

    Route::post('/store', 'App\Http\Controllers\WaybillCargo2Controller@store')->name('Waybillcargo2.store');

    Route::get('/{id}/edit', 'App\Http\Controllers\WaybillCargo2Controller@edit')->name('Waybillcargo2.edit');

    Route::put('{id}/update', 'App\Http\Controllers\WaybillCargo2Controller@update')->name('Waybillcargo2.update');

    Route::delete('{id}/deleteCargo2', 'App\Http\Controllers\WaybillCargo2Controller@deletecargo2')->name('Waybillcargo2.delete');

    Route::post('/createWaybillTrip', 'App\Http\Controllers\WaybillCargo2Controller@createTrip')->name('api.Waybillcargo2.createTrip');

    Route::get('/printWaybill', 'App\Http\Controllers\WaybillCargo2Controller@printWaybill')->name('api.Waybillcargo2.printWaybill');
});

///////////////////waybill CARGO

Route::group(['prefix' => 'Waybillcargo-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\WaybillCargoController@index')->name('WaybillCargo');
    Route::get('/create', 'App\Http\Controllers\WaybillCargoController@create')->name('Waybill.create_cargo');
    Route::get('/{id}/edit', 'App\Http\Controllers\WaybillCargoController@edit')->name('Waybill.edit_cargo');
    Route::post('/store', 'App\Http\Controllers\WaybillCargoController@store')->name('Waybill.store_cargo');
    Route::put('{id}/update', 'App\Http\Controllers\WaybillCargoController@update')->name('Waybill.update_cargo');


});

//////////////////waybill CAR

Route::group(['prefix' => 'Waybillcar-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\WaybillCarController@index')->name('WaybillCar');

    Route::get('/create', 'App\Http\Controllers\WaybillCarController@create')->name('Waybill.create_car');

    Route::get('/create2', 'App\Http\Controllers\WaybillCarController@create2')->name('Waybill.create_car2');

    Route::get('{id}/createBack', 'App\Http\Controllers\WaybillCarController@createBack')->name('Waybill.create_back_car');

    Route::get('/{id}/edit', 'App\Http\Controllers\WaybillCarController@edit')->name('Waybill.edit_car');

    Route::post('/store', 'App\Http\Controllers\WaybillCarController@store')->name('Waybill.store_car');

    Route::post('/deleteCar', 'App\Http\Controllers\WaybillCarController@deleteCar')->name('Waybill.delete_car');

    Route::post('/storePhoto', 'App\Http\Controllers\WaybillCarController@storePhoto')->name('Waybill.storePhoto');

    Route::put('{id}/update', 'App\Http\Controllers\WaybillCarController@update')->name('Waybill.update_car');

    Route::get('/getDiscountTypeByCompany', 'App\Http\Controllers\WaybillCarController@getDiscountTypeByCompany')->name('Waybill.getDiscountTypeByCompany');

    Route::get('/getPriceList', 'App\Http\Controllers\Api\WaybillCarController@getPrice')->name('car-getprice');

    Route::get('/getContractsList', 'App\Http\Controllers\Api\WaybillCarController@getContractsList')->name('car-getContractsList');

    Route::get('/getSenderInfo', 'App\Http\Controllers\Api\WaybillCarController@getSenderInfo')->name('car-getSenderInfo');

    Route::get('/getBrandDetails', 'App\Http\Controllers\WaybillCarController@getBrandDetails')->name('car-getBrandDetails');

    Route::get('/getBrandDetailsCarSize', 'App\Http\Controllers\WaybillCarController@getBrandDetailsCarSize')->name('car-getBrandDetailsCarSize');

    Route::get('/addDaysToDate', 'App\Http\Controllers\WaybillCarController@addDaysToDate')->name('car-addDaysToDate');

    Route::get('/checkWaybillByPlateNo', 'App\Http\Controllers\WaybillCarController@checkWaybillByPlateNo')->name('car-checkWaybillByPlateNo');

    Route::get('/checkWaybillByPlateChaseNo', 'App\Http\Controllers\WaybillCarController@checkWaybillByPlateChaseNo')->name('car-checkWaybillByPlateChaseNo');

    Route::post('/updateCars', 'App\Http\Controllers\WaybillCarController@updateCars')->name('Waybill.car-updateCars');


});

//////////////////waybill CAR Deliver
Route::group(['prefix' => 'WaybillCar-deliver', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\WaybillCarDeliverController@create')->name('WaybillCarDeliver.create');

    Route::post('/store', 'App\Http\Controllers\WaybillCarDeliverController@store')->name('WaybillCarDeliver.store');

    Route::get('/getCustomerWaybills', 'App\Http\Controllers\WaybillCarDeliverController@getCustomerWaybills')->name('WaybillCarDeliver.getCustomerWaybills');

});

Route::group(['prefix' => 'employee-certificates', 'middleware' => 'auth'], function () {

    Route::get('{emp}/create', 'App\Http\Controllers\EmployeeCertificatesController@create')->name('employee-certificates-create');

    Route::post('/store', 'App\Http\Controllers\EmployeeCertificatesController@store')->name('employee-certificates-store');

    Route::get('{id}/edit', 'App\Http\Controllers\EmployeeCertificatesController@edit')->name('employee-certificates-edit');

    Route::put('{id}/update', 'App\Http\Controllers\EmployeeCertificatesController@update')->name('employee-certificates-update');

    Route::delete('{id}/delete', 'App\Http\Controllers\EmployeeCertificatesController@delete')->name('employee-certificates-delete');

    Route::get('/download', 'App\Http\Controllers\EmployeeCertificatesController@downloadPdf')->name('employee-certificates-downloadPdf');
});

Route::group(['prefix' => 'employee-experience', 'middleware' => 'auth'], function () {

    Route::get('{emp}/create', 'App\Http\Controllers\EmployeeExperienceController@create')->name('employee-experience-create');

    Route::post('/store', 'App\Http\Controllers\EmployeeExperienceController@store')->name('employee-experience-store');

    Route::get('{id}/edit', 'App\Http\Controllers\EmployeeExperienceController@edit')->name('employee-experience-edit');

    Route::put('{id}/update', 'App\Http\Controllers\EmployeeExperienceController@update')->name('employee-experience-update');

    Route::delete('{id}/delete', 'App\Http\Controllers\EmployeeExperienceController@delete')->name('employee-experience-delete');

    Route::get('/download', 'App\Http\Controllers\EmployeeExperienceController@downloadPdf')->name('employee-experience-downloadPdf');
});

Route::group(['prefix' => 'monthly-salaries', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\MonthlySalariesController@index')->name('monthly-salaries');

    Route::get('/{id}/show', 'App\Http\Controllers\MonthlySalariesController@show')->name('monthly-salaries.show');

    Route::get('/create', 'App\Http\Controllers\MonthlySalariesController@create')->name('monthly-salaries.create');

    Route::post('/store', 'App\Http\Controllers\MonthlySalariesController@store')->name('monthly-salaries.store');

});

Route::group(['prefix' => 'employee-requests', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\EmployeeRequestController@index')->name('employee-requests');

    Route::get('/create', 'App\Http\Controllers\EmployeeRequestController@create')->name('employee-requests-create');

    Route::post('/store', 'App\Http\Controllers\EmployeeRequestController@store')->name('employee-requests-store');

    Route::get('{id}/edit', 'App\Http\Controllers\EmployeeRequestController@edit')->name('employee-requests-edit');

    Route::put('{id}/update', 'App\Http\Controllers\EmployeeRequestController@update')->name('employee-requests-update');

    Route::delete('{id}/delete', 'App\Http\Controllers\EmployeeRequestController@delete')->name('employee-requests-delete');

    ////////////التامين الطبي
    Route::group(['prefix' => 'medical-insurance-request'], function () {

        Route::post('/storeMedicalInsurance', 'App\Http\Controllers\EmployeeRequestController@storeMedicalInsurance')
            ->name('employee-requests-store-medical-insurance');

        Route::get('{id}/editMedicalInsurance', 'App\Http\Controllers\EmployeeRequestController@editMedicalInsurance')
            ->name('employee-requests-edit-medical-insurance');

        Route::put('{id}/updateMedicalInsurance', 'App\Http\Controllers\EmployeeRequestController@updateMedicalInsurance')
            ->name('employee-requests-update-medical-insurance');


    });


    /////////////////تسليم العهده
    Route::group(['prefix' => 'hand-over-request'], function () {

        Route::post('/storeHandOver', 'App\Http\Controllers\EmployeeRequestController@storeHandOver')
            ->name('employee-requests-store-hand-over');

        Route::get('{id}/editHandOver', 'App\Http\Controllers\EmployeeRequestController@editHandOver')
            ->name('employee-requests-edit-hand-over');

        Route::put('{id}/updateHandOver', 'App\Http\Controllers\EmployeeRequestController@updateHandOver')
            ->name('employee-requests-update-hand-over');


    });

    /////////////////اجراء جزاء
    Route::group(['prefix' => 'panel-action-request'], function () {

        Route::post('/storePanelAction', 'App\Http\Controllers\EmployeeRequestController@storePanelAction')
            ->name('employee-requests-store-panel-action');

        Route::get('{id}/editPanelAction', 'App\Http\Controllers\EmployeeRequestController@editPanelAction')
            ->name('employee-requests-edit-panel-action');

        Route::put('{id}/updatePanelAction', 'App\Http\Controllers\EmployeeRequestController@updatePanelAction')
            ->name('employee-requests-update-panel-action');
    });

    /////////////////طلب السلفه
    Route::group(['prefix' => 'ancestors-request'], function () {

        Route::post('/storeAncestorsRequest', 'App\Http\Controllers\EmployeeRequestController@storeAncestorsRequest')
            ->name('employee-requests-store-ancestors-request');

        Route::get('{id}/editAncestorsRequest', 'App\Http\Controllers\EmployeeRequestController@editAncestorsRequest')
            ->name('employee-requests-edit-ancestors-request');

        Route::put('{id}/updateAncestorsRequest', 'App\Http\Controllers\EmployeeRequestController@updateAncestorsRequest')
            ->name('employee-requests-update-ancestors-request');


    });

    /////////////////طلب ايقاف العمل
    Route::group(['prefix' => 'stop-working-request'], function () {

        Route::get('/get-diff-date', 'App\Http\Controllers\EmployeeRequestController@getDifferenceDate')->name('requests.diffDate');

        Route::post('/storeStopWorkingRequest', 'App\Http\Controllers\EmployeeRequestController@storeStopWorkingRequest')
            ->name('employee-requests-store-stop-working-request');

        Route::get('{id}/editStopWorkingRequest', 'App\Http\Controllers\EmployeeRequestController@editStopWorkingRequest')
            ->name('employee-requests-edit-stop-working-request');

        Route::put('{id}/updateStopWorkingRequest', 'App\Http\Controllers\EmployeeRequestController@updateStopWorkingRequest')
            ->name('employee-requests-update-stop-working-request');

    });


    /////تكليف بمهمه عمل
    Route::group(['prefix' => 'job-assignment-request'], function () {

        Route::get('/get-branches', 'App\Http\Controllers\EmployeeRequestController@getBranches')->name('job-assignment.getBranches');


        Route::post('/storeJobAssignmentRequest', 'App\Http\Controllers\EmployeeRequestController@storeJobAssignmentRequest')
            ->name('employee-requests-store-job-assignment-request');

        Route::get('{id}/editJobAssignmentRequest', 'App\Http\Controllers\EmployeeRequestController@editJobAssignmentRequest')
            ->name('employee-requests-edit-job-assignment-request');

        Route::put('{id}/updateJobAssignmentRequest', 'App\Http\Controllers\EmployeeRequestController@updateJobAssignmentRequest')
            ->name('employee-requests-update-job-assignment-request');

    });


    /////طلب اخلاء طرف
    Route::group(['prefix' => 'job-leave-request'], function () {

        Route::post('/storeJobLeaveRequest', 'App\Http\Controllers\EmployeeRequestController@storeJobLeaveRequest')
            ->name('employee-requests-store-job-leave-request');

        Route::get('{id}/editJobLeaveRequest', 'App\Http\Controllers\EmployeeRequestController@editJobLeaveRequest')
            ->name('employee-requests-edit-job-leave-request');

        Route::put('{id}/updateJobLeaveRequest', 'App\Http\Controllers\EmployeeRequestController@updateJobLeaveRequest')
            ->name('employee-requests-update-job-leave-request');

    });

    //    تقييم موظف
    Route::group(['prefix' => 'evaluate-employee-request'], function () {

        Route::post('/storeEmployeeEvaluationRequest', 'App\Http\Controllers\EmployeeRequestController@storeEmployeeEvaluationRequest')
            ->name('employee-requests-store-evaluation-request');

        Route::get('{id}/editEmployeeEvaluationRequest', 'App\Http\Controllers\EmployeeRequestController@editEmployeeEvaluationRequest')
            ->name('employee-requests-edit-evaluation-request');

        Route::put('{id}/updateEmployeeEvaluationRequest', 'App\Http\Controllers\EmployeeRequestController@updateEmployeeEvaluationRequest')
            ->name('employee-requests-update-evaluation-request');

    });

//    طلب استقاله
    Route::group(['prefix' => 'resignation-request'], function () {

        Route::post('/storeResignationRequest', 'App\Http\Controllers\EmployeeRequestController@storeResignationRequest')
            ->name('employee-requests-store-resignation-request');

        Route::get('{id}/editResignationRequest', 'App\Http\Controllers\EmployeeRequestController@editResignationRequest')
            ->name('employee-requests-edit-resignation-request');

        Route::put('{id}/updateResignationRequest', 'App\Http\Controllers\EmployeeRequestController@updateResignationRequest')
            ->name('employee-requests-update-resignation-request');

    });

    ///طلب تصفيه حساب
    Route::group(['prefix' => 'reckoning-request'], function () {

        Route::post('/storeReckoningRequest', 'App\Http\Controllers\EmployeeRequestController@storeReckoningRequest')
            ->name('employee-requests-store-reckoning-request');

        Route::get('{id}/editReckoningRequest', 'App\Http\Controllers\EmployeeRequestController@editReckoningRequest')
            ->name('employee-requests-edit-reckoning-request');

        Route::put('{id}/updateReckoningRequest', 'App\Http\Controllers\EmployeeRequestController@updateReckoningRequest')
            ->name('employee-requests-update-reckoning-request');


        Route::get('/getEmployeeVacationData', 'App\Http\Controllers\EmployeeRequestController@getEmployeeVacationData')
            ->name('get-employee-vacation-data');

    });

    Route::group(['prefix' => 'direct-request'], function () {

        Route::get('/{id}/edit', 'App\Http\Controllers\EmployeeDirectRequestController@edit')->name('employee.direct.request.edit');

        Route::put('/{id}/update', 'App\Http\Controllers\EmployeeDirectRequestController@update')->name('employee.direct.request.update');

    });

});

//    'namespace' => 'App\Http\Controllers'


//////////////////carrent

Route::group(['prefix' => 'car-rent', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers'], function () {

//    Route::get('/create', 'CarRentController@create')->name('car-rent.create');

    Route::get('/', 'CarRent\CarRentController@index')->name('car-rent.index');

    Route::get('/create', 'CarRent\CarRentController@create')->name('car-rent.create');

    Route::post('/store', 'CarRent\CarRentController@store')->name('car-rent.store');

    Route::post('/storePhoto', 'CarRent\CarRentController@storePhoto')->name('car-rent.storePhoto');

    Route::get('{id}/edit', 'CarRent\CarRentController@edit')->name('car-rent.edit');

    Route::get('{id}/contractComplete', 'CarRent\CarRentController@contractComplete')->name('car-rent.contractComplete');

    Route::put('{id}/update', 'CarRent\CarRentController@update')->name('car-rent.update');
    Route::get('/update/status/balance', 'CarRent\CarRentController@UpdateStatusAndBalance')->name('car-rent.update.status.balance');
    Route::get('/make/invoices', 'CarRent\CarRentController@makeInvvoices')->name('car-rent.make.invoice');

    Route::get('/getContract', 'CarRent\CarRentController@getContract')->name('api.car-rent.contract');

    Route::post('/addInvoiceWithJournalWhenCloseContract', 'CarRent\CarRentController@addInvoiceWithJournalWhenCloseContract')->name('car-rent.addInvoiceWithJournalWhenCloseContract');

//    image sketch
    Route::get('/car-sketch', function () {
        return view('CarRent.Contract.img');
    })->name('car.rent.sketch-info');

    Route::group(['prefix' => 'Api'], function () {

        Route::get('/customer', 'CarRent\Api\CarRentController@getCustomerData')->name('api.carRent.customer');

        Route::get('/getPriceList', 'CarRent\CarRentController@getPriceList')->name('api.carRent.getPriceList');

        Route::get('/cars', 'CarRent\Api\CarRentController@getCars')->name('api.carRent.getCars');
        Route::get('/carDetails', 'CarRent\Api\CarRentController@getCarDetails')->name('api.carRent.getCarDetails');

        Route::get('/policies', 'Api\CarRentController@getAllPolicies')->name('api.carRent.getAllPolicies');

        Route::get('/branches', 'Api\CarRentController@getAllBranches')->name('api.carRent.getAllBranches');

        Route::get('/extended_coverage', 'Api\CarRentController@getExtendedCoverage')->name('api.carRent.getExtendedCoverage');

        Route::get('/getDifferenceDate', 'CarRent\CarRentController@getDifferenceDate')->name('car-rent.contract.getDifferenceDate');

        Route::get('/getContractEndDate', 'CarRent\CarRentController@getContractEndDate')->name('car-rent.contract.getContractEndDate');

        Route::get('/getRentPolicyTaxRate', 'CarRent\CarRentController@getRentPolicyTaxRate')->name('car-rent.contract.getRentPolicyTaxRate');

        Route::post('/SaveContract', 'CarRent\CarRentController@SaveContract')->name('api.car-rent.contract.SaveContract');
        Route::post('/CreateContractWeb', 'CarRent\CarRentController@CreateContractWeb')->name('api.car-rent.contract.CreateContractWeb');
        Route::post('/CancelContract', 'CarRent\CarRentController@CancelContract')->name('api.car-rent.contract.cancelContract');
        Route::post('/CloseContract', 'CarRent\CarRentController@CloseContract')->name('api.car-rent.contract.CloseContract');
        Route::post('/SuspendContract', 'CarRent\CarRentController@SuspendContract')->name('api.car-rent.contract.SuspendContract');
        Route::get('/getContractPDF/{id}', 'CarRent\CarRentController@getContractPDF')->name('api.car-rent.contract.getContractPDF');
        Route::get('/getSummarizedContractPDF/{id}', 'CarRent\CarRentController@getSummarizedContractPDF')->name('api.car-rent.contract.getSummarizedContractPDF');

    });

//////////////////car-rent-customer

    Route::group(['prefix' => 'customers'], function () {

        Route::get('/', 'CarRent\CustomerController@index')->name('car-rent.customers.index');

        Route::get('/create', 'CarRent\CustomerController@create')->name('car-rent.customers.create');

        Route::get('/create/all', 'CarRent\CustomerController@create')->name('car-rent.customers.all_create');

        Route::post('/store', 'CarRent\CustomerController@store')->name('car-rent.customers.store');

        Route::get('{id}/edit', 'CarRent\CustomerController@edit')->name('car-rent.customers.show');

        Route::get('{id}/edit/all', 'CarRent\CustomerController@edit')->name('car-rent.customers.edit');

        Route::put('{id}/update', 'CarRent\CustomerController@update')->name('car-rent.customers.update');

        Route::get('{id}/block', 'CarRent\CustomerController@blockCustomer')->name('car-rent.customers.block');

        Route::post('/blockStore', 'CarRent\CustomerController@blockStore')->name('car-rent.customers.blockStore');

        Route::get('/getDifferenceDate', 'CarRent\CustomerController@getDifferenceDate')->name('car-rent.customers.getDifferenceDate');

    });

    Route::group(['prefix' => 'invoices'], function () {

        Route::get('/', 'CarRent\InvoiceController@index')->name('car-rent.invoices');

        Route::get('/create', 'CarRent\InvoiceController@create')->name('car-rent.invoices.create');

        Route::post('/store', 'CarRent\InvoiceController@store')->name('car-rent.invoices.store');

        Route::get('{id}/show', 'CarRent\InvoiceController@show')->name('car-rent.invoices.show');

        Route::get('/getCustomerContracts', 'CarRent\InvoiceController@getCustomerContracts')->name('car-rent.invoices.getCustomerContracts');

        Route::get('/getDifferenceDate', 'CarRent\InvoiceController@getDifferenceDate')->name('car-rent.invoices.getDifferenceDate');

    });

    Route::resource('brands', 'CarRent\CarRentBrandController');
    Route::resource('brandDts', 'CarRent\CarRentBrandDtController');
    Route::resource('movements', 'CarRent\CarRentMovementController');
});

//////////////////////// TRUCKAccident //////////////////

Route::group(['prefix' => 'truck-accident', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers'], function () {

    Route::get('/', 'TruckAccidentController@index')->name('truck-accident.index');

    Route::get('/create', 'TruckAccidentController@create')->name('truck-accident.create');

    Route::post('/store', 'TruckAccidentController@store')->name('truck-accident.store');

    Route::get('{id}/edit', 'TruckAccidentController@edit')->name('truck-accident.edit');

    Route::put('{id}/update', 'TruckAccidentController@update')->name('truck-accident.update');

    Route::delete('{id}/delete', 'TruckAccidentController@delete')->name('truck-accident.delete');

});
//////////////////////// CarAccident //////////////////

Route::group(['prefix' => 'car-accident', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers'], function () {

    Route::get('/', 'CarAccidentController@index')->name('car-accident.index');

    Route::get('/create', 'CarAccidentController@create')->name('car-accident.create');

    Route::post('/store', 'CarAccidentController@store')->name('car-accident.store');

    Route::get('{id}/edit', 'CarAccidentController@edit')->name('car-accident.edit');

    Route::put('{id}/update', 'CarAccidentController@update')->name('car-accident.update');

    Route::delete('{id}/delete', 'CarAccidentController@delete')->name('car-accident.delete');

});


//كشف الحساب
Route::group(['prefix' => 'report-accounts', 'namespace' => 'App\Http\Controllers', 'middleware' => 'auth'], function () {

//    Route::get('/create', 'GlController@create')->name('gl.create');

    Route::get('/', 'GlController@create')->name('acc-reports');

    Route::post('/store', 'GlController@store')->name('gl.store');

    Route::get('/get-company-accounts', 'GlController@getAccounts')->name('gl.get-company-accounts');

    Route::get('/getFormSubTypes', 'GlController@getFormSubTypes')->name('gl.getFormSubTypes');

    Route::get('/getCostCenterDts', 'GlController@getCostCenterDts')->name('api.accounts-reports.costCenterDts');

});

//////////////////car_rent_models

Route::group(['prefix' => 'CarRentModel-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\CarRent\CarRentModelControllers@index')->name('CarRentModel');

    Route::get('/create', 'App\Http\Controllers\CarRent\CarRentModelControllers@create')->name('CarRentModel.create');

    Route::get('{counter}/{car_model}/create2', 'App\Http\Controllers\CarRent\CarRentModelControllers@create2')->name('CarRentModel.create2');

    Route::get('/{id}/edit', 'App\Http\Controllers\CarRent\CarRentModelControllers@edit')->name('CarRentModel.edit');

    Route::post('/store', 'App\Http\Controllers\CarRent\CarRentModelControllers@store')->name('CarRentModel.store');

    Route::put('{id}/update', 'App\Http\Controllers\CarRent\CarRentModelControllers@update')->name('CarRentModel.update');

    Route::group(['prefix' => 'Api'], function () {

        Route::get('/get-brand-details', 'App\Http\Controllers\CarRent\CarRentModelControllers@getBrandDetails')->name('api.get-brand-details');

    });


});

//////////////////car_rent-cars

Route::group(['prefix' => 'CarRentCars-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\CarRent\CarRentCarsControllers@index')->name('CarRentCars');

    Route::get('/{id}/edit', 'App\Http\Controllers\CarRent\CarRentCarsControllers@edit')->name('CarRentCars.edit');

    Route::post('/store', 'App\Http\Controllers\CarRent\CarRentCarsControllers@store')->name('CarRentCars.store');

    Route::put('{id}/update', 'App\Http\Controllers\CarRent\CarRentCarsControllers@update')->name('CarRentCars.update');

});

//////////////////car_rent-CarRentPriceList

Route::group(['prefix' => 'CarRentPriceList-add', 'middleware' => 'auth'], function () {

    Route::get('/', 'App\Http\Controllers\CarRent\CarRentPriceListControllers@index')->name('CarRentPriceList');

    Route::get('/create', 'App\Http\Controllers\CarRent\CarRentPriceListControllers@create')->name('CarRentPriceList.create');

    Route::get('/{id}/edit', 'App\Http\Controllers\CarRent\CarRentPriceListControllers@edit')->name('CarRentPriceList.edit');

    Route::post('/store', 'App\Http\Controllers\CarRent\CarRentPriceListControllers@store')->name('CarRentPriceList.store');

    Route::put('{id}/update', 'App\Http\Controllers\CarRent\CarRentPriceListControllers@update')->name('CarRentPriceList.update');

    Route::delete('/deletePriceListDt', 'App\Http\Controllers\CarRent\CarRentPriceListControllers@deletePriceListDt')->name('CarRentPriceList.deletePriceListDt');

    Route::group(['prefix' => 'api'], function () {

        Route::get('/getCarModels', 'App\Http\Controllers\CarRent\CarRentPriceListControllers@getCarModels')->name('CarRentPriceList.getCarModels');

    });


});

//////////////////car_rent-contract
////
//Route::group(['prefix' => 'CarRentContract-add', 'middleware' => 'auth'], function () {
//
//    Route::get('/', 'App\Http\Controllers\CarRent\ContractControllers@index')->name('CarRentContract');
//    Route::get('/create', 'App\Http\Controllers\CarRent\ContractControllers@create')->name('CarRentContract.create');
//    Route::get('/{id}/edit', 'App\Http\Controllers\CarRent\ContractControllers@edit')->name('CarRentContract.edit');
//    Route::post('/store', 'App\Http\Controllers\CarRent\ContractControllers@store')->name('CarRentContract.store');
//    Route::put('{id}/update', 'App\Http\Controllers\CarRent\ContractControllers@update')->name('CarRentContract.update');
//
//});


//////////////القيود اليوميه
Route::group(['prefix' => 'journal-entries', 'namespace' => 'App\Http\Controllers', 'middleware' => 'auth'], function () {

    Route::get('/', 'JournalEntriesController@index')->name('journal-entries');

    Route::get('/create', 'JournalEntriesController@create')->name('journal-entries.create');

    Route::get('/create-sheet', 'JournalEntriesController@createSheet')->name('journal-entries.create-sheet');

    Route::post('/store', 'JournalEntriesController@store')->name('journal-entries.store');

    Route::post('/storeFromSheet', 'JournalEntriesController@storeFromSheet')->name('journal-entries.storeFromSheet');

    Route::get('{id}/edit', 'JournalEntriesController@edit')->name('journal-entries.edit');

    Route::get('{id}/edit_2', 'JournalEntriesController@edit_2')->name('journal-entries.edit_2');

    Route::put('{id}/update_2', 'JournalEntriesController@update_2')->name('journal-entries.update_2');
    
    Route::put('{id}/update', 'JournalEntriesController@update')->name('journal-entries.update');

    Route::get('{id}/show', 'JournalEntriesController@show')->name('journal-entries.show');

    Route::post('approveJournal', 'JournalEntriesController@approveJournal')->name('journal-entries.approveJournal');

    Route::post('updateJournalStatus', 'JournalEntriesController@updateJournalStatus')->name('journal-entries.updateJournalStatus');

    Route::get('export', 'JournalEntriesController@export')->name('journal-entries.export');

    Route::group(['prefix' => 'Api'], function () {

        Route::get('/company-data', 'JournalEntriesController@getCompanyData')->name('api.journal-entries.company-data');

        Route::get('/get-data', 'JournalEntriesController@getData')->name('api.journal-entries.get-data');

        Route::delete('/delete', 'JournalEntriesController@delete')->name('api.journal-entries.delete');

    });

});


Route::group(['prefix' => 'notifications', 'namespace' => 'App\Http\Controllers', 'middleware' => 'auth'], function () {

    Route::get('/', 'NotificationsController@index')->name('notifications-index');

});


///////////////////apis
///
Route::group(['prefix' => 'Api'], function () {

    
    Route::get('company-group/companies', 'App\Http\Controllers\Api\CompanyGroupController@getCompanies')->name('api.company-group.companies');

    Route::get('company/get-applications', 'App\Http\Controllers\Api\ApplicationsController@getApplications')->name('api.company.applications');

    Route::get('/company/account-periods', 'App\Http\Controllers\Api\MonthlyAdditionController@getCompany')->name('api.company.accountPeriod');

    Route::delete('/delete', 'App\Http\Controllers\Api\MonthlyAdditionController@deleteEmployeeVariableDetails')->name('api.addition-detail.delete');

    Route::get('/company/employee-variable-code', 'App\Http\Controllers\Api\MonthlyAdditionController@getEmployeeVariableCode')->name('api.employee-variable');

    Route::get('/company/employee-variable-code-2', 'App\Http\Controllers\Api\MonthlyAdditionController@getEmployeeVariableCodeSystemCode')->name('api.employee-variable2');

    Route::get('/employee/branch', 'App\Http\Controllers\Api\MonthlyAdditionController@getEmployeeBranch')->name('api.employee.branch');

    Route::get('/employee/getVariableFactor', 'App\Http\Controllers\Api\MonthlyAdditionController@getEmployeeVariableFactor')->name('api.employee.variableFactor');

    Route::get('company/get-branches', 'App\Http\Controllers\Api\CompanyController@getBranches')->name('api.company.branches');

    Route::get('contract-salaries', 'App\Http\Controllers\Api\EmployeeContractSalaryController@getSalaries')->name('api.contract.salaries');

    Route::delete('contract-salary-delete', 'App\Http\Controllers\Api\EmployeeContractSalaryController@deleteSalaryDetail')->name('api.contract.salary-delete');

    Route::group(['prefix' => 'monthly-deductions', 'middleware' => 'auth'], function () {

        Route::get('/company/accounts-periods', 'App\Http\Controllers\Api\MonthlyDeductionsController@getCompany')->name('api.deduction-company');

        Route::get('/employee/branch', 'App\Http\Controllers\Api\MonthlyDeductionsController@getEmployeeBranch')->name('api.deduction-employee.branch');

        Route::get('/employee/getVariableFactor', 'App\Http\Controllers\Api\MonthlyDeductionsController@getEmployeeVariableFactor')->name('api.deduction-employee.variableFactor');

        Route::delete('/delete', 'App\Http\Controllers\Api\MonthlyDeductionsController@deleteEmployeeVariableDetails')->name('api.deduction-detail.delete');

    });

    Route::group(['prefix' => 'notifications'], function () {

        Route::get('/seen', 'App\Http\Controllers\Api\NotificationController@markSeen')->name('notification-seen');

        Route::get('/markAllAsRead', 'App\Http\Controllers\Api\NotificationController@markAllAsRead')->name('notifications-all-seen');

        Route::get('/', 'App\Http\Controllers\NotificationsController@index')->name('notifications-index');

    });

    Route::get('/get-employees', 'App\Http\Controllers\Api\EmployeeRequestsController@getEmployees')->name('get-employees');

    Route::get('/date', 'App\Http\Controllers\Api\CompanyController@getDate')->name('api.getDate');

    Route::get('/date2', 'App\Http\Controllers\Api\CompanyController@getDate2')->name('api.getDate2');

    Route::get('/diff-date', 'App\Http\Controllers\Api\CompanyController@getDifferenceDate')->name('api.getDiffDate');

    Route::get('/trucks', 'App\Http\Controllers\Api\WayPillsController@getTrucks')->name('api.company.trucks');

    Route::get('/system-codes', 'App\Http\Controllers\Api\SystemCodesController@getSalaryItems')->name('api.system-codes.get');

    Route::get('/employee-request/getDays', 'App\Http\Controllers\Api\EmployeeRequestsController@getDifferenceDate')->name('employee-requests-getDays');

    Route::get('/employee-request/getVacationBalance', 'App\Http\Controllers\Api\EmployeeRequestsController@getVacationBalance')->name('employee-requests-getDays-now');

    Route::get('/get-employee', 'App\Http\Controllers\Api\EmployeeRequestsController@getEmployee')->name('get-employee');

    Route::get('/system-codes', 'App\Http\Controllers\Api\SystemCodesController@getSalaryItems')->name('api.system-codes.get');

    Route::group(['prefix' => 'waybill-cargo2'], function () {

        Route::get('/getPriceList', 'App\Http\Controllers\Api\WaybillCargo2Controller@getPriceList')->name('cargo2-getPriceList');

    });


    Route::get('truck-driver', 'App\Http\Controllers\Api\WayPillsController@getTruckDriver')->name('api.waybill.truck.driver');

    Route::get('get-truck', 'App\Http\Controllers\Api\WayPillsController@getTruck')->name('api.waybill.truck');

    Route::get('truck-driver-id', 'App\Http\Controllers\Api\WayPillsController@getTruckDriverId')->name('api.waybill.truck.driver-id');

    Route::get('customer-type', 'App\Http\Controllers\Api\WaybillcarController@getcustomertype')->name('api.waybill.customer.type');

    Route::get('getCountWaybillsDaily', 'App\Http\Controllers\Api\WaybillcarController@getCountWaybillsDaily')->name('api.waybill.getCountWaybillsDaily');


    Route::get('/get-employee-request', 'App\Http\Controllers\Api\EmployeeRequestsController@getEmployeeRequest')->name('get-employee-request');

    Route::get('/get-employee-vacation', 'App\Http\Controllers\Api\EmployeeRequestsController@getVacation')->name('get-employee-vacation');

    Route::get('/employee-request/getVacationBalance', 'App\Http\Controllers\Api\EmployeeRequestsController@getVacationBalance')
        ->name('employee-requests-getDays-now');

});

// ----------------  MAINTENANCE ROUTE ----------------------------------------------------------
Route::group(['prefix' => 'maintenance-card', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Maintenance'], function () {


    Route::get('/', 'MaintenanceCardController@index')->name('maintenance-card.index');
    Route::get('/create', 'MaintenanceCardController@create')->name('maintenance-card.create');
    Route::get('card/create', 'MaintenanceCardController@cardCreate')->name('maintenance-card.card.create');
    Route::post('/item/store/', 'MaintenanceCardController@itemStore')->name('maintenance-card.item.store');
    Route::post('/item/delete/', 'MaintenanceCardController@itemDelete')->name('maintenance-card.item.delete');
    Route::post('/store', 'MaintenanceCardController@store')->name('maintenance-card.store');
    Route::post('card/store', 'MaintenanceCardController@CardStore')->name('maintenance-card.card.store');
    Route::get('/edit/{mntns_cards_id}', 'MaintenanceCardController@edit')->name('maintenance-card.edit');
    Route::post('/update', 'MaintenanceCardController@cardUpdate')->name('maintenance-card.update');
    Route::post('/addBondWithJournal', 'MaintenanceCardController@addBondWithJournal')->name('maintenance-card.addBondWithJournal');
    Route::post('/addBondWithJournal2', 'MaintenanceCardController@addBondWithJournal2')->name('maintenance-card.addBondWithJournal2');
    Route::post('/storeBond', 'MaintenanceCardController@storeBond')->name('maintenance-card.storeBond');

    Route::get('/add/technician', 'MaintenanceCardController@addTech')->name('maintenance-card.add.tech');
    Route::post('/save/technician', 'MaintenanceCardController@saveTech')->name('maintenance-card.save.tech');
    Route::post('/close', 'MaintenanceCardController@closeCard')->name('maintenance-card.close');

    Route::get('/get/customer/by/type', 'MaintenanceCardController@getCustomerByType')->name('maintenance-card.get.customer.by.type');
    Route::get('/get/car/by/customer/id', 'MaintenanceCardController@getCarListByCustomerId')->name('maintenanced-car.get.car.list.by.customer.id');
    Route::get('/get/part/by/warehouses/id', 'MaintenanceCardController@getPartByWarehousesType')->name('maintenanced-card.get.part.list.by.warehouses.id');
    Route::get('/get/card/item/modal', 'MaintenanceCardController@getItemModal')->name('maintenanced-car.get.item.modal');

    /////////////////////صيانه فحص السيارات
    Route::group(['prefix' => 'maintenanceCardCheck'], function () {

        Route::get('/', 'MaintenanceCardCheckController@index')->name('maintenanceCardCheck');

        Route::post('/store', 'MaintenanceCardCheckController@store')->name('maintenanceCardCheck.store');

        Route::get('/create', 'MaintenanceCardCheckController@create')->name('maintenanceCardCheck.create');

        Route::get('{id}/edit', 'MaintenanceCardCheckController@edit')->name('maintenanceCardCheck.edit');

        Route::put('{id}/update', 'MaintenanceCardCheckController@update')->name('maintenanceCardCheck.update');

        Route::delete('/delete', 'MaintenanceCardCheckController@delete')->name('maintenanceCardCheck.delete');

        Route::post('/storePhoto', 'MaintenanceCardCheckController@storePhoto')->name('maintenanceCardCheck.storePhoto');

        Route::post('/addBondWithJournal', 'MaintenanceCardCheckController@addBondWithJournal')->name('maintenanceCardCheck.addBondWithJournal');

        Route::get('/getCustomerData', 'MaintenanceCardCheckController@getCustomerData')->name('maintenanceCardCheck.getCustomerData');

        Route::get('/getBrandDts', 'MaintenanceCardCheckController@getBrandDts')->name('maintenanceCardCheck.getBrandDts');

        Route::get('/getMntnsCarDt', 'MaintenanceCardCheckController@getMntnsCarDt')->name('maintenanceCardCheck.getMntnsCarDt');

        Route::get('/getMaintenanceType', 'MaintenanceCardCheckController@getMaintenanceType')->name('maintenanceCardCheck.getMaintenanceType');

    });

});


Route::group(['prefix' => 'store-accounts', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Store'], function () {

    Route::get('/', 'StoreAccountsController@index')->name('storeAccounts.index');

    Route::get('/create', 'StoreAccountsController@create')->name('storeAccounts.create');

    Route::post('/store', 'StoreAccountsController@store')->name('storeAccounts.store');

    Route::get('{id}/edit', 'StoreAccountsController@edit')->name('storeAccounts.edit');

    Route::put('{id}/update', 'StoreAccountsController@update')->name('storeAccounts.update');

    Route::get('/getBranches', 'StoreAccountsController@getBranches')->name('storeAccounts.getBranches');

});


Route::group(['prefix' => 'maintenance-car', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Maintenance'], function () {

    Route::get('/', 'MaintenanceCarController@index')->name('maintenance-car.index');
    Route::get('/data', 'MaintenanceCarController@data')->name('maintenance-car.data');
    Route::get('/data/table/{companyId}', 'MaintenanceCarController@dataTable')->name('maintenance-car.data.table');
    Route::get('/create', 'MaintenanceCarController@create')->name('maintenance-car.create');
    Route::post('/store', 'MaintenanceCarController@store')->name('maintenance-car.store');
    Route::get('/edit/{uuid}', 'MaintenanceCarController@edit')->name('maintenance-car.edit');
    Route::post('/update', 'MaintenanceCarController@update')->name('maintenance-car.update');


});

Route::group(['prefix' => 'maintenance-type', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Maintenance'], function () {

    Route::get('/', 'MaintenanceTypeController@index')->name('maintenance-type.index');
    Route::get('/create', 'MaintenanceTypeController@create')->name('maintenance-type.create');
    Route::post('/store', 'MaintenanceTypeController@store')->name('maintenance-type.store');
    Route::get('/edit/{uuid}', 'MaintenanceTypeController@edit')->name('maintenance-type.edit');
    Route::post('/update', 'MaintenanceTypeController@update')->name('maintenance-type.update');
});

// ---------------- END MAINTENANCE ROUTE ----------------------------------------------------------

// ----------------   STORE ITEM ROUTE --------------------------------------------------------------
Route::group(['prefix' => 'store-item', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Store'], function () {

    Route::get('/', 'StoreItemController@index')->name('store-item.index');
    Route::get('/data', 'StoreItemController@data')->name('store-item.data');
    Route::get('/data/table/{companyId}', 'StoreItemController@dataTable')->name('store-item.data.table');
    Route::post('/store', 'StoreItemController@store')->name('store-item.store');
    Route::get('/edit/{uuid}', 'StoreItemController@edit')->name('store-item.edit');
    Route::post('/update', 'StoreItemController@update')->name('store-item.update');
    Route::post('/delete', 'StoreItemController@delete')->name('store-item.delete');
    Route::get('/search', 'StoreItemController@search')->name('store-item.search');
    Route::get('/details', 'StoreItemController@getItemDetails')->name('store-item.details');

});
//----------------- END STORE ITEM ROUTE ------------------------------------------------------------

// ----------------   STORE client ROUTE --------------------------------------------------------------
Route::group(['prefix' => 'store-client', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Store'], function () {

    Route::get('/', 'StoreClientController@index')->name('store-client.index');
    Route::get('/data', 'StoreClientController@data')->name('store-client.data');
    Route::get('/data/table/{companyId}', 'StoreClientController@dataTable')->name('store-client.data.table');
    Route::get('/create', 'StoreClientController@create')->name('store-client.create');
    Route::post('/store', 'StoreClientController@store')->name('store-client.store');
    Route::get('/{customer_id}/edit', 'StoreClientController@edit')->name('store-client.edit');
    Route::put('{customer_id}/update', 'StoreClientController@update')->name('store-client.update');
});
//----------------- END STORE client ROUTE ------------------------------------------------------------

// ----------------   STORE PURCHASE ROUTE --------------------------------------------------------------
Route::group(['prefix' => 'store-purchase', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Store'], function () {

    Route::get('/request', 'PurchaseController@index')->name('store-purchase-request.index')->defaults('page', 'request');
    Route::get('request/data', 'PurchaseController@data')->name('store-purchase-request.data')->defaults('page', 'request');
    Route::get('request/data/table/{companyId}', 'PurchaseController@dataTable')->name('store-purchase-request.data.table')->defaults('page', 'request');
    Route::post('request/store', 'PurchaseController@store')->name('store-purchase-request.store')->defaults('page', 'request');
    Route::post('request/store/item', 'PurchaseController@storeItem')->name('store-item-purchase-request.store')->defaults('page', 'request');
    Route::get('request/edit/{uuid}', 'PurchaseController@edit')->name('store-purchase-request.edit')->defaults('page', 'request');
    Route::post('request/update', 'PurchaseController@update')->name('store-purchase-request.update')->defaults('page', 'request');


    Route::get('/order', 'PurchaseController@index')->name('store-purchase-order.index')->defaults('page', 'order');
    Route::get('order/data', 'PurchaseController@data')->name('store-purchase-order.data')->defaults('page', 'order');
    Route::get('order/data/table/{companyId}', 'PurchaseController@dataTable')->name('store-purchase-order.data.table')->defaults('page', 'order');
    Route::post('order/store', 'PurchaseController@store')->name('store-purchase-order.store')->defaults('page', 'order');
    Route::post('order/store/item', 'PurchaseController@storeItem')->name('store-item-purchase-order.store')->defaults('page', 'order');
    Route::get('order/edit/{uuid}', 'PurchaseController@edit')->name('store-purchase-order.edit')->defaults('page', 'order');
    Route::post('order/update', 'PurchaseController@update')->name('store-purchase-order.update')->defaults('page', 'order');

    Route::post('order/direct', 'PurchaseController@store')->name('store-purchase-direct-order.store')->defaults('page', 'direct_order');
    Route::get('order/direct/{uuid}', 'PurchaseController@edit')->name('store-purchase-direct-order.edit')->defaults('page', 'direct_order');
    Route::post('order/direct/store/item', 'PurchaseController@storeItem')->name('store-item-purchase-direct-order.store')->defaults('page', 'direct_order');

    Route::get('/receiving', 'PurchaseController@index')->name('store-purchase-receiving.index')->defaults('page', 'receiving');
    Route::get('receiving/data', 'PurchaseController@data')->name('store-purchase-receiving.data')->defaults('page', 'receiving');
    Route::get('receiving/data/table/{companyId}', 'PurchaseController@dataTable')->name('store-purchase-receiving.data.table')->defaults('page', 'receiving');
    Route::post('receiving/store', 'PurchaseController@store')->name('store-purchase-receiving.store')->defaults('page', 'receiving');
    Route::post('trans/store', 'PurchaseController@store')->name('store-purchase-trans.store')->defaults('page', 'trans');
    Route::get('receiving/edit/{uuid}', 'PurchaseController@edit')->name('store-purchase-receiving.edit')->defaults('page', 'receiving');
    Route::post('receiving/storeBondJournal', 'PurchaseController@addBondWithJournal')->name('store-purchase-receiving-bondJournal');
    Route::post('receiving/updateItemSerial', 'PurchaseController@updateItemSerial')->name('store-purchase-receiving-updateItemSerial');
    Route::post('receiving/deleteItemSerial', 'PurchaseController@deleteItemSerial')->name('store-purchase-receiving-deleteItemSerial');

    Route::post('receiving/new', 'PurchaseController@store')->name('store-purchase-new-receiving.store')->defaults('page', 'new_receving');
    Route::get('receiving/new/{uuid}', 'PurchaseController@edit')->name('store-purchase-new-receiving.edit')->defaults('page', 'new_receving');
    Route::post('receiving/store/item', 'PurchaseController@storeItem')->name('store-item-purchase-new-receiving.store')->defaults('page', 'new_receving');
    Route::post('receiving/update', 'PurchaseController@update')->name('store-item-purchase-new-receiving.update');
    //Route::put('receiving/{uuid}/update', 'PurchaseController@update')->name('update-item-purchase-new-receiving.update');

    Route::get('/return', 'PurchaseController@index')->name('store-purchase-return.index')->defaults('page', 'return');
    Route::get('return/data', 'PurchaseController@data')->name('store-purchase-return.data')->defaults('page', 'return');
    Route::get('return/data/table/{companyId}', 'PurchaseController@dataTable')->name('store-purchase-return.data.table')->defaults('page', 'return');
    Route::post('return/store', 'PurchaseController@store')->name('store-purchase-return.store')->defaults('page', 'return');
    Route::get('return/edit/{uuid}', 'PurchaseController@edit')->name('store-purchase-return.edit')->defaults('page', 'return');
    Route::post('return/addBondWithJournal2', 'PurchaseController@addBondWithJournal2')->name('store-purchase-return.addBondWithJournal2');

    Route::post('/delete', 'PurchaseController@deleteItem')->name('store-purchase.delete');
    Route::get('/get/request', 'PurchaseController@getPurchaseByCode')->name('get-store-purchase-by-code');

});
//----------------- END STORE PURCHASE ROUTE ------------------------------------------------------------

// ----------------   STORE SALES ROUTE --------------------------------------------------------------
Route::group(['prefix' => 'store-sales', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Store'], function () {

    Route::get('/quote', 'StoreSalesController@index')->name('store-sales-quote.index')->defaults('page', 'quote');
    Route::get('quote/data', 'StoreSalesController@data')->name('store-sales-quote.data')->defaults('page', 'quote');
    Route::get('quote/data/table/{companyId}', 'StoreSalesController@dataTable')->name('store-sales-quote.data.table')->defaults('page', 'quote');
    Route::post('quote/store', 'StoreSalesController@store')->name('store-sales-quote.store')->defaults('page', 'quote');
    Route::post('quote/store/item', 'StoreSalesController@storeItem')->name('store-item-sales-quote.store')->defaults('page', 'quote');
    Route::get('quote/edit/{uuid}', 'StoreSalesController@edit')->name('store-sales-quote.edit')->defaults('page', 'quote');

    Route::get('/inv', 'StoreSalesController@index')->name('store-sales-inv.index')->defaults('page', 'inv');
    Route::get('inv/data', 'StoreSalesController@data')->name('store-sales-inv.data')->defaults('page', 'inv');
    Route::get('inv/data/table/{companyId}', 'StoreSalesController@dataTable')->name('store-sales-inv.data.table')->defaults('page', 'inv');
    Route::post('inv/store', 'StoreSalesController@store')->name('store-sales-inv.store')->defaults('page', 'inv');
    Route::post('inv/update', 'StoreSalesController@update')->name('store-sales-inv.update');
    Route::post('inv/storeBond', 'StoreSalesController@storeBond')->name('store-sales-inv.storeBond');
    Route::get('inv/getItems', 'StoreSalesController@getItems')->name('store-sales-inv.getItems');
    Route::post('inv/approveOneInvoice', 'StoreSalesController@approveOneInvoice')->name('store-sales-inv.approveOneInvoice');
    Route::post('inv/approveAllInvoice', 'StoreSalesController@approveAllInvoice')->name('store-sales-inv.approveAllInvoice');

    Route::post('inv/store/new', 'StoreSalesController@store')->name('store-sales-inv.storenew')->defaults('page', 'invnew');
    Route::get('inv/new/{uuid}', 'StoreSalesController@edit')->name('store-sales-invnew.edit')->defaults('page', 'invnew');
    Route::post('invnew/store/item', 'StoreSalesController@storeItem')->name('store-item-sales-invnew.store')->defaults('page', 'invnew');
    Route::post('invnew/generate', 'StoreSalesController@generate')->name('store-item-sales-invnew.generate')->defaults('page', 'invnew');

    Route::post('inv/store/item', 'StoreSalesController@storeItem')->name('store-item-purchase-inv.store')->defaults('page', 'inv');
    Route::get('inv/edit/{uuid}', 'StoreSalesController@edit')->name('store-sales-inv.edit')->defaults('page', 'inv');
    Route::post('inv/addBondWithJournal2', 'StoreSalesController@addBondWithJournal2')->name('store-sales-inv.addBondWithJournal2');

    Route::get('/return', 'StoreSalesController@index')->name('store-sales-return.index')->defaults('page', 'return');
    Route::get('return/data', 'StoreSalesController@data')->name('store-sales-return.data')->defaults('page', 'return');
    Route::get('return/data/table/{companyId}', 'StoreSalesController@dataTable')->name('store-sales-return.data.table')->defaults('page', 'return');
    Route::post('return/store', 'StoreSalesController@store')->name('store-sales-return.store')->defaults('page', 'return');
    Route::post('return/store/item', 'StoreSalesController@storeItem')->name('store-item-purchase-return.store')->defaults('page', 'return');
    Route::get('return/edit/{uuid}', 'StoreSalesController@edit')->name('store-sales-return.edit')->defaults('page', 'return');
    Route::post('return/addBondWithJournal', 'StoreSalesController@addBondWithJournal')->name('store-sales-return.addBondWithJournal');


    Route::post('/delete', 'StoreSalesController@deleteItem')->name('store-sales.delete');
    Route::get('/get/request', 'StoreSalesController@getPurchaseByCode')->name('get-store-sales-by-code');

});
//----------------- END STORE SALES ROUTE ------------------------------------------------------------

// ----------------   STORE TRANSFER ROUTE --------------------------------------------------------------
Route::group(['prefix' => 'store-transfer', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Store'], function () {

    Route::get('/trans', 'StoreTransferController@index')->name('store-transfer-trans.index')->defaults('page', 'trans');
    Route::get('trans/data', 'StoreTransferController@data')->name('store-transfer-trans.data')->defaults('page', 'trans');
    Route::get('trans/data/table/{companyId}', 'StoreTransferController@dataTable')->name('store-transfer-trans.data.table')->defaults('page', 'trans');
    Route::post('trans/store', 'StoreTransferController@store')->name('store-transfer-trans.store')->defaults('page', 'trans');
    Route::post('trans/store/item', 'StoreTransferController@storeItem')->name('store-item-sales-trans.store')->defaults('page', 'trans');
    Route::get('trans/edit/{uuid}', 'StoreTransferController@edit')->name('store-transfer-trans.edit')->defaults('page', 'trans');

    Route::post('trans/req/store', 'StoreTransferController@store')->name('store-transfer-trans-req.store')->defaults('page', 'trans_form_req');

    Route::post('/delete', 'StoreTransferController@deleteItem')->name('store-transfer.delete');

    Route::post('quote/store/file', 'StoreSalesController@store')->name('store-sales-quote-from-file.store')->defaults('page', 'quote_from_file');
    Route::post('/get/data/from/file', 'StoreSalesController@getDataFromFile')->name('store-sales.get.data.from.file');
});
//----------------- END STORE TRANSFER ROUTE ------------------------------------------------------------

// ----------------   STORE SALES ROUTE --------------------------------------------------------------
Route::group(['prefix' => 'sales-car', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\SalesCar'], function () {

    Route::get('/request', 'SalesCarController@index')->name('sales-car-request.index')->defaults('page', 'request');
    Route::get('request/data', 'SalesCarController@data')->name('sales-car-request.data')->defaults('page', 'request');
    Route::get('request/data/table/{companyId}', 'SalesCarController@dataTable')->name('sales-car-request.data.table')->defaults('page', 'request');
    Route::post('request/store', 'SalesCarController@store')->name('sales-car-request.store')->defaults('page', 'request');
    Route::post('request/store/item', 'SalesCarController@storeItem')->name('store-item-sales-car-request.store')->defaults('page', 'request');
    Route::get('request/edit/{uuid}', 'SalesCarController@edit')->name('sales-car-request.edit')->defaults('page', 'request');

    Route::get('/order', 'SalesCarController@index')->name('sales-car-order.index')->defaults('page', 'order');
    Route::get('order/data', 'SalesCarController@data')->name('sales-car-order.data')->defaults('page', 'order');
    Route::get('order/data/table/{companyId}', 'SalesCarController@dataTable')->name('sales-car-order.data.table')->defaults('page', 'order');
    Route::post('order/store', 'SalesCarController@store')->name('sales-car-order.store')->defaults('page', 'order');
    Route::post('order/store/item', 'SalesCarController@storeItem')->name('store-item-sales-car-order.store')->defaults('page', 'order');
    Route::get('order/edit/{uuid}', 'SalesCarController@edit')->name('sales-car-order.edit')->defaults('page', 'order');

    Route::post('order/direct', 'SalesCarController@store')->name('sales-car-direct-order.store')->defaults('page', 'direct_order');
    Route::get('order/direct/{uuid}', 'SalesCarController@edit')->name('sales-car-direct-order.edit')->defaults('page', 'direct_order');
    Route::post('order/direct/store/item', 'SalesCarController@storeItem')->name('store-item-sales-car-direct-order.store')->defaults('page', 'direct_order');

    Route::get('/receiving', 'SalesCarController@index')->name('sales-car-receiving.index')->defaults('page', 'receiving');
    Route::get('receiving/data', 'SalesCarController@data')->name('sales-car-receiving.data')->defaults('page', 'receiving');
    Route::get('receiving/data/table/{companyId}', 'SalesCarController@dataTable')->name('sales-car-receiving.data.table')->defaults('page', 'receiving');
    Route::post('receiving/store', 'SalesCarController@store')->name('sales-car-receiving.store')->defaults('page', 'receiving');
    Route::post('trans/store', 'SalesCarController@store')->name('sales-car-trans.store')->defaults('page', 'trans');
    Route::post('receiving/store/item', 'SalesCarController@storeItem')->name('store-item-sales-car-receiving.store')->defaults('page', 'receiving');
    Route::get('receiving/edit/{uuid}', 'SalesCarController@edit')->name('sales-car-receiving.edit')->defaults('page', 'receiving');
    Route::post('receiving/storeBondJournal', 'SalesCarController@addBondWithJournal')->name('store-sales-car-receiving-bondJournal');

    Route::post('receiving/direct', 'SalesCarController@store')->name('sales-car-direct-receiving.store')->defaults('page', 'direct_receiving');
    Route::get('receiving/direct/{uuid}', 'SalesCarController@edit')->name('sales-car-direct-receiving.edit')->defaults('page', 'direct_receiving');
    Route::post('receiving/direct/store/item', 'SalesCarController@storeItem')->name('store-item-sales-car-direct-receiving.store')->defaults('page', 'direct_receiving');


    Route::get('/quote', 'SalesCarController@index')->name('sales-car-quote.index')->defaults('page', 'quote');
    Route::get('quote/data', 'SalesCarController@data')->name('sales-car-quote.data')->defaults('page', 'quote');
    Route::get('quote/data/table/{companyId}', 'SalesCarController@dataTable')->name('sales-car-quote.data.table')->defaults('page', 'quote');
    Route::post('quote/store', 'SalesCarController@store')->name('sales-car-quote.store')->defaults('page', 'quote');
    Route::post('quote/store/item', 'SalesCarController@storeItem')->name('store-item-sales-car-quote.store')->defaults('page', 'quote');
    Route::get('quote/edit/{uuid}', 'SalesCarController@edit')->name('sales-car-quote.edit')->defaults('page', 'quote');


    Route::get('/inv', 'SalesCarController@index')->name('sales-car-inv.index')->defaults('page', 'inv');
    Route::get('inv/data', 'SalesCarController@data')->name('sales-car-inv.data')->defaults('page', 'inv');
    Route::get('inv/data/table/{companyId}', 'SalesCarController@dataTable')->name('sales-car-inv.data.table')->defaults('page', 'inv');
    Route::post('inv/store', 'SalesCarController@store')->name('sales-car-inv.store')->defaults('page', 'inv');
    Route::post('inv/store/item', 'SalesCarController@storeItem')->name('store-item-sales-car-inv.store')->defaults('page', 'inv');
    Route::get('inv/edit/{uuid}', 'SalesCarController@edit')->name('sales-car-inv.edit')->defaults('page', 'inv');

    Route::post('inv/direct/store', 'SalesCarController@store')->name('sales-car-direct-inv.store')->defaults('page', 'direct_inv');
    Route::get('inv/direct/{uuid}', 'SalesCarController@edit')->name('sales-car-direct-inv.edit')->defaults('page', 'direct_inv');
    Route::post('inv/direct/store/item', 'SalesCarController@storeItem')->name('store-item-sales-car-direct-inv.store')->defaults('page', 'direct_inv');
    Route::post('inv/addBondWithJournal2', 'SalesCarController@addBondWithJournal2')->name('sales-car-inv.addBondWithJournal2');
    Route::post('inv/storeBond', 'SalesCarController@storeBond')->name('sales-car-inv.storeBond');

    Route::get('/return', 'SalesCarController@index')->name('sales-car-return.index')->defaults('page', 'return');
    Route::get('return/data', 'SalesCarController@data')->name('sales-car-return.data')->defaults('page', 'return');
    Route::get('return/data/table/{companyId}', 'SalesCarController@dataTable')->name('sales-car-return.data.table')->defaults('page', 'return');

    Route::post('return/purcahse/store', 'SalesCarController@store')->name('sales-car-purcahse-return.store')->defaults('page', 'return-purcahse');
    Route::get('return/purcahse/edit/{uuid}', 'SalesCarController@edit')->name('sales-car-purcahse-return.edit')->defaults('page', 'return-purcahse');

    Route::post('return/sales/store', 'SalesCarController@store')->name('sales-car-sales-return.store')->defaults('page', 'return-sales');
    Route::get('return/sales/edit/{uuid}', 'SalesCarController@edit')->name('sales-car-sales-return.edit')->defaults('page', 'return-sales');

    Route::post('/update/header', 'SalesCarController@updateHeader')->name('car-sales.header.update');
    Route::post('/delete', 'SalesCarController@deleteItem')->name('car-sales.delete');
    Route::get('/get/request', 'SalesCarController@getSalesByCode')->name('get-store-sales-by-code');
    Route::get('/get/brand/details/by/brand', 'SalesCarController@getBrandDTbyBrand')->name('get.brand.dt.by.brand');


    Route::get('car/', 'CarController@index')->name('sales-car.index');
    Route::get('car/data', 'CarController@data')->name('sales-car.data');
    Route::get('car/data/table/{companyId}', 'CarController@dataTable')->name('sales-car.data.table');
    Route::get('car/show/{uuid}', 'CarController@show')->name('sales-car.show');
    Route::post('car/update', 'CarController@showupdate')->name('sales-car.update');
    Route::get('/get/car/by/brand/details', 'CarController@getCarbyBrand')->name('get.car.by.brand.details');

});
//----------------- END STORE SALES ROUTE ------------------------------------------------------------

// ----------------   SALES CAR TRANSFER ROUTE --------------------------------------------------------------
Route::group(['prefix' => 'sales-car-transfer', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\SalesCar'], function () {

    Route::get('/trans', 'SalesCarTransController@index')->name('sales-car-transfer-trans.index')->defaults('page', 'trans');
    Route::get('trans/data', 'SalesCarTransController@data')->name('sales-car-transfer-trans.data')->defaults('page', 'trans');
    Route::get('trans/data/table/{companyId}', 'SalesCarTransController@dataTable')->name('sales-car-transfer-trans.data.table')->defaults('page', 'trans');
    Route::post('trans/store', 'SalesCarTransController@store')->name('sales-car-transfer-trans.store')->defaults('page', 'trans');
    Route::post('trans/store/item', 'SalesCarTransController@storeItem')->name('sales-car-item-sales-trans.store')->defaults('page', 'trans');
    Route::get('trans/edit/{uuid}', 'SalesCarTransController@edit')->name('sales-car-transfer-trans.edit')->defaults('page', 'trans');

    Route::post('trans/req/store', 'SalesCarTransController@store')->name('sales-car-transfer-trans-req.store')->defaults('page', 'direct_trans_form_req');
    Route::post('/delete', 'SalesCarTransController@deleteItem')->name('sales-car-transfer.delete');

});
//----------------- END SALES CAR TRANSFER ROUTE ------------------------------------------------------------

// ----------------  STORE STOCKING ROUTE --------------------------------------------------------------
Route::group(['prefix' => 'store-stocking', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Store'], function () {

    Route::get('/index', 'StockingController@index')->name('store-stocking.index');
    Route::get('/data', 'StockingController@data')->name('store-stocking.data');
    Route::get('/data/table/{companyId}', 'StockingController@dataTable')->name('store-stocking.data.table');
    Route::post('/store', 'StockingController@store')->name('store-stocking.store');
    Route::post('/update/qty', 'StockingController@updateStockingQty')->name('store-stocking.update.stocking.qty');
    Route::get('/edit/{uuid}', 'StockingController@edit')->name('store-stocking.edit');
    Route::get('/show/{uuid}', 'StockingController@show')->name('store-stocking.show');
    Route::post('/add/item', 'StockingController@addItem')->name('store-stocking.add.item');

    Route::post('/stocking', 'StockingController@stocking')->name('store-stocking.stocking');
    Route::get('get/item/location', 'StockingController@getItemLocation')->name('store-stocking.get.item.location');
    Route::get('get/item/by/code', 'StockingController@getItemByCode')->name('get.item.by.code');
    Route::post('/delete', 'StockingController@deleteItem')->name('store-stocking.delete');


});
//----------------- END STORE STOCKING ROUTE ------------------------------------------------------------

// ----------------  STORE STOCKING ROUTE --------------------------------------------------------------
Route::group(['prefix' => 'sms', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\SMS'], function () {

    Route::get('providers/index', 'SmsProvidersController@index')->name('sms-providers.index');
    Route::get('providers/data', 'SmsProvidersController@data')->name('sms-providers.data');
    Route::get('providers/data/table/{companyId}', 'SmsProvidersController@dataTable')->name('sms-providers.data.table');
    Route::post('providers/store', 'SmsProvidersController@store')->name('sms-providers.store');
    Route::post('providers/update', 'SmsProvidersController@update')->name('sms-providers.update');
    Route::get('providers/edit/{uuid}', 'SmsProvidersController@edit')->name('sms-providers.edit');
    Route::post('providers/delete', 'SmsProvidersController@deleteProvider')->name('sms-providers.delete');


    Route::get('category/index', 'SmsCategoryController@index')->name('sms-category.index');
    Route::get('category/data', 'SmsCategoryController@data')->name('sms-category.data');
    Route::get('category/data/table/{companyId}', 'SmsCategoryController@dataTable')->name('sms-category.data.table');
    Route::post('category/store', 'SmsCategoryController@store')->name('sms-category.store');
    Route::post('category/update', 'SmsCategoryController@update')->name('sms-category.update');
    Route::get('category/edit/{uuid}', 'SmsCategoryController@edit')->name('sms-category.edit');
    Route::post('category/delete', 'SmsCategoryController@deleteCategory')->name('sms-category.delete');

    Route::get('queue/index', 'SmsQueueController@index')->name('sms-queue.index');
    Route::get('queue/data', 'SmsQueueController@data')->name('sms-queue.data');
    Route::get('queue/data/table/{companyId}', 'SmsQueueController@dataTable')->name('sms-queue.data.table');


});
//----------------- END STORE STOCKING ROUTE ------------------------------------------------------------

Route::group(['prefix' => 'waybill-cargo3', 'middleware' => 'auth'], function () {

    Route::get('/getPriceList', 'App\Http\Controllers\Api\WaybillCargo2Controller@getwaybillinfo')->name('cargo3-getwaybillinfo');
    Route::get('/getwaybillteckit', 'App\Http\Controllers\Api\WaybillCargo2Controller@getwaybillteckit')->name('cargo3-getwaybillteckit');
});

Route::group(['prefix' => 'waybill-cargo4', 'middleware' => 'auth'], function () {


    Route::get('/getPriceList', 'App\Http\Controllers\Api\WaybillCargo2Controller@getprice')->name('cargo4-getprice');
    Route::get('/getPriceListD', 'App\Http\Controllers\Api\WaybillCargo2Controller@getpriceD')->name('cargoD-getprice');
    Route::get('/getPriceList91', 'App\Http\Controllers\Api\WaybillCargo2Controller@getprice91')->name('cargo91-getprice');
    Route::get('/getPriceList95', 'App\Http\Controllers\Api\WaybillCargo2Controller@getprice95')->name('cargo95-getprice');

});

route::get('n/test', function () {
    $trip = App\Models\TripHd::where('trip_hd_id', '=', 5805)->first();
    $data = NaqlAPIController::createTrip($trip);

    return $data;

});


route::get('n/testw', function () {
    $trip = App\Models\WaybillHd::where('waybill_id', '=', 19074)->first();
    $data = NaqlWayAPIController::createTrip($trip);

    return $trip;

});


Route::group(['prefix' => 'Financial-form', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\FinancialForm'], function () {

    Route::group(['prefix' => 'sub-types'], function () {

        Route::get('/', 'FinancialFormSubTypesController@index')->name('financial-sub-types');

        Route::post('/store', 'FinancialFormSubTypesController@store')->name('financial-sub-types.store');

        Route::put('/{id}/update', 'FinancialFormSubTypesController@update')->name('financial-sub-types.update');

        Route::delete('/{id}/delete', 'FinancialFormSubTypesController@delete')->name('financial-sub-types.delete');

    });


    Route::group(['prefix' => 'sub-types-dts'], function () {

        Route::post('/store', 'FinancialFormSubTypesDtController@store')->name('financial-sub-types-dt.store');

        Route::put('/{id}/update', 'FinancialFormSubTypesDtController@update')->name('financial-sub-types-dt.update');

        Route::delete('/{id}/delete', 'FinancialFormSubTypesDtController@delete')->name('financial-sub-types-dt.delete');

        Route::post('/addAccOrder', 'FinancialFormSubTypesDtController@addAccOrder')->name('financial-sub-types-dt.addAccOrder');

    });


    Route::group(['prefix' => 'accounts'], function () {

        Route::get('/{id}/all', 'FinancialFormAccountController@index')->name('financial-account.index');

        Route::get('/{id}/create', 'FinancialFormAccountController@create')->name('financial-account.create');

        Route::post('/store', 'FinancialFormAccountController@store')->name('financial-account.store');

        Route::get('{id}/edit', 'FinancialFormAccountController@edit')->name('financial-account.edit');

        Route::put('{id}/update', 'FinancialFormAccountController@update')->name('financial-account.update');

        Route::delete('{id}/delete', 'FinancialFormAccountController@delete')->name('financial-account.delete');

        Route::get('/getAccountsList', 'FinancialFormAccountController@getAccountsList')->name('financial-account.getAccountsList');

        Route::post('/addAccOrder', 'FinancialFormAccountController@addAccOrder')->name('financial-account.addAccOrder');

    });

});


////////////////////stations
Route::group(['prefix' => 'stations', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Station'], function () {

    Route::group(['prefix' => 'maintenanceCard'], function () {

        Route::get('/', 'MaintenanceCardController@index')->name('maintenanceCard');

        Route::get('/create', 'MaintenanceCardController@create')->name('maintenanceCard.create');

        Route::get('/createM', 'MaintenanceCardController@createM')->name('maintenanceCard.createM');


        Route::get('{id}/create2', 'MaintenanceCardController@create2')->name('maintenanceCard.create2');

        Route::post('/store', 'MaintenanceCardController@store')->name('maintenanceCard.store');

        Route::put('{id}/update', 'MaintenanceCardController@update')->name('maintenanceCard.update');

        Route::post('/storeDetails', 'MaintenanceCardController@storeDetails')->name('maintenanceCard.storeDetails');

        Route::get('/getMaintenanceTypeDts', 'MaintenanceCardController@getMaintenanceTypeDts')
            ->name('maintenanceCard.getMaintenanceTypeDts');

        Route::get('/getMaintenanceTypes', 'MaintenanceCardController@getMaintenanceTypes')
            ->name('maintenanceCard.getMaintenanceTypes');

        Route::get('/getStoreItemDt', 'MaintenanceCardController@getStoreItemDt')->name('maintenanceCard.getStoreItemDt');

        Route::get('{id}/deleteItem', 'MaintenanceCardController@deleteItem')->name('maintenanceCard.deleteItem');

    });

    ////////////////////صيانه التانكات
    Route::group(['prefix' => 'maintenanceCardTanks'], function () {

        Route::get('/', 'MaintenanceCardTanksController@index')->name('maintenanceCardTanks');

        Route::get('/create', 'MaintenanceCardTanksController@create')->name('maintenanceCardTanks.create');

        Route::get('/createM', 'MaintenanceCardTanksController@createM')->name('maintenanceCardTanks.createM');


        Route::get('{id}/create2', 'MaintenanceCardTanksController@create2')->name('maintenanceCardTanks.create2');

        Route::post('/store', 'MaintenanceCardTanksController@store')->name('maintenanceCardTanks.store');

        Route::put('{id}/update', 'MaintenanceCardTanksController@update')->name('maintenanceCardTanks.update');

        Route::post('/storeDetails', 'MaintenanceCardTanksController@storeDetails')->name('maintenanceCardTanks.storeDetails');

        Route::get('/getMaintenanceTypeDts', 'MaintenanceCardTanksController@getMaintenanceTypeDts')
            ->name('maintenanceCardTanks.getMaintenanceTypeDts');

        Route::get('/getMaintenanceTypes', 'MaintenanceCardTanksController@getMaintenanceTypes')
            ->name('maintenanceCardTanks.getMaintenanceTypes');

        Route::get('/getStoreItemDt', 'MaintenanceCardTanksController@getStoreItemDt')->name('maintenanceCardTanks.getStoreItemDt');

        Route::get('{id}/deleteItem', 'MaintenanceCardTanksController@deleteItem')->name('maintenanceCardTanks.deleteItem');

    });


    ////////////////////////صيانه نظام الحريق
    Route::group(['prefix' => 'maintenanceCardFireSystem'], function () {

        Route::get('/', 'MaintenanceCardFireSystemController@index')->name('maintenanceCardFireSystem');

        Route::get('/create', 'MaintenanceCardFireSystemController@create')->name('maintenanceCardFireSystem.create');

        Route::get('/createM', 'MaintenanceCardFireSystemController@createM')->name('maintenanceCardFireSystem.createM');

        Route::get('{id}/create2', 'MaintenanceCardFireSystemController@create2')->name('maintenanceCardFireSystem.create2');

        Route::post('/store', 'MaintenanceCardFireSystemController@store')->name('maintenanceCardFireSystem.store');

        Route::put('{id}/update', 'MaintenanceCardFireSystemController@update')->name('maintenanceCardFireSystem.update');

        Route::post('/storeDetails', 'MaintenanceCardFireSystemController@storeDetails')->name('maintenanceCardFireSystem.storeDetails');

        Route::get('/getMaintenanceTypeDts', 'MaintenanceCardFireSystemController@getMaintenanceTypeDts')
            ->name('maintenanceCardFireSystem.getMaintenanceTypeDts');

        Route::get('/getMaintenanceTypes', 'MaintenanceCardFireSystemController@getMaintenanceTypes')
            ->name('maintenanceCardFireSystem.getMaintenanceTypes');

        Route::get('/getStoreItemDt', 'MaintenanceCardFireSystemController@getStoreItemDt')->name('maintenanceCardFireSystem.getStoreItemDt');

        Route::get('{id}/deleteItem', 'MaintenanceCardFireSystemController@deleteItem')->name('maintenanceCardFireSystem.deleteItem');

    });

    /////////////////////صيانه الخدمات
    Route::group(['prefix' => 'maintenanceCardServices'], function () {

        Route::get('/', 'maintenanceCardServicesController@index')->name('maintenanceCardServices');

        Route::get('/create', 'maintenanceCardServicesController@create')->name('maintenanceCardServices.create');

        Route::get('/createM', 'maintenanceCardServicesController@createM')->name('maintenanceCardServices.createM');

        Route::get('{id}/create2', 'maintenanceCardServicesController@create2')->name('maintenanceCardServices.create2');

        Route::post('/store', 'maintenanceCardServicesController@store')->name('maintenanceCardServices.store');

        Route::put('{id}/update', 'maintenanceCardServicesController@update')->name('maintenanceCardServices.update');

        Route::post('/storeDetails', 'maintenanceCardServicesController@storeDetails')->name('maintenanceCardServices.storeDetails');

        Route::get('/getMaintenanceTypeDts', 'maintenanceCardServicesController@getMaintenanceTypeDts')
            ->name('maintenanceCardServices.getMaintenanceTypeDts');

        Route::get('/getMaintenanceTypes', 'maintenanceCardServicesController@getMaintenanceTypes')
            ->name('maintenanceCardServices.getMaintenanceTypes');

        Route::get('/getStoreItemDt', 'maintenanceCardServicesController@getStoreItemDt')->name('maintenanceCardServices.getStoreItemDt');

        Route::get('{id}/deleteItem', 'maintenanceCardServicesController@deleteItem')->name('maintenanceCardServices.deleteItem');

    });

    Route::group(['prefix' => 'Store-Purchase'], function () {

//    طلب شراء محطات
        Route::group(['prefix' => 'Request'], function () {

            Route::get('/create', 'Store\Purchase\RequestController@create')->name('stations-storePurchaseRequest.create');

            Route::post('/store', 'Store\Purchase\RequestController@store')->name('stations-storePurchaseRequest.store');

            Route::post('/storePurchaseOrder', 'Store\Purchase\RequestController@storePurchaseOrder')->name('stations-storePurchaseRequest.storePurchaseOrder');

            Route::get('/getItemDetails', 'Store\Purchase\RequestController@getItemDetails')->name('stations-storePurchaseRequest.getItemDetails');

        });

//        اذن استلام
        Route::group(['prefix' => 'Receiving'], function () {

            Route::get('/create', 'Store\Purchase\ReceivingController@create')->name('stations-storePurchaseReceiving.create');

            Route::post('/storeNewReceiving', 'Store\Purchase\ReceivingController@storeNewReceiving')->name('stations-storePurchaseReceiving.storeNewReceiving');

            Route::post('/storeReceivingFromOrder', 'Store\Purchase\ReceivingController@storeReceivingFromOrder')->name('stations-storePurchaseReceiving.storeReceivingFromOrder');

            Route::get('/getStoreHd', 'Store\Purchase\ReceivingController@getStoreHd')->name('stations-storePurchaseReceiving.getStoreHd');

        });

    });


});


//////////////////////////////evaluations
///
///1-internal inspections
///

Route::group(['prefix' => 'evaluations', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Evaluation'], function () {

    ///////////////////تفتيش داخلي
    Route::group(['prefix' => 'internalInspection'], function () {

        Route::get('/', 'InternalInspectionController@index')->name('internal-inspection');

        Route::get('/create', 'InternalInspectionController@create')->name('internal-inspection.create');

        Route::post('/store', 'InternalInspectionController@store')->name('internal-inspection.store');

        Route::get('{id}/show', 'InternalInspectionController@show')->name('internal-inspection.show');

        Route::get('/createM', 'InternalInspectionController@createM')->name('internal-inspection.createM');

        Route::post('/storeM', 'InternalInspectionController@storeM')->name('internal-inspection.storeM');

    });

    Route::group(['prefix' => 'QualityEvaluation'], function () {

        Route::get('/', 'QualityEvaluationController@index')->name('quality-evaluation');

        Route::get('/create', 'QualityEvaluationController@create')->name('quality-evaluation.create');

        Route::post('/store', 'QualityEvaluationController@store')->name('quality-evaluation.store');

        Route::get('{id}/show', 'QualityEvaluationController@show')->name('quality-evaluation.show');

        Route::get('/createM', 'QualityEvaluationController@createM')->name('quality-evaluation.createM');
        Route::post('/storeM', 'QualityEvaluationController@storeM')->name('quality-evaluation.storeM');

    });

});


// ----------------   FUEL STATION ROUTE --------------------------------------------------------------
Route::group(['prefix' => 'fuel-station', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Qserv'], function () {

    Route::get('/', 'FuelStationController@index')->name('fuel-station.index');
    Route::get('/data', 'FuelStationController@data')->name('fuel-station.data');
    Route::get('/data/table', 'FuelStationController@dataTable')->name('fuel-station.data.table');
    Route::get('/edit/price', 'FuelStationController@editPrice')->name('fuel-station.edit.price');
    Route::post('/update/price', 'FuelStationController@updatePrice')->name('fuel-station.update.price');
    Route::post('/confirm/price', 'FuelStationController@confirmPrice')->name('fuel-station.confirm.price');
    Route::get('/get/branch', 'FuelStationController@getBranchByCompany')->name('fuel-station.get.branch');

    Route::get('/price/history', 'FuelStationController@priceHistory')->name('fuel-station.price.history');
    Route::get('/price/history/data', 'FuelStationController@priceHistoryData')->name('fuel-station.price.history.data');

});
//----------------- END FUEL STATION  ROUTE ------------------------------------------------------------

// ----------------   FUEL STATION ROUTE --------------------------------------------------------------
Route::group(['prefix' => 'petro-app', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Petro'], function () {

    Route::post('trip/sendRequest', 'PetroAppController@sendRequest')->name('trip.petro-app.send.request')->defaults('type', 'trip');
    Route::post('waybill/sendRequest', 'PetroAppController@sendRequest')->name('waybill.petro-app.send.request')->defaults('type', 'waybill');

});
//----------------- END FUEL STATION  ROUTE ------------------------------------------------------------


Route::post('/webCam', 'App\Http\Controllers\WebCamController@store')->name('store-photo');
Route::get('get/trans', function () {
    $fromDate = Carbon::now()->subDays(1)->format('m/d/Y');//'02/06/2023';
    $toDate = Carbon::now()->format('m/d/Y');//'02/07/2023';
    $branches = App\Models\Branch::where('station_id', '!=', null)->get();

    $res = collect();
    foreach ($branches as $branch) {
        ini_set('max_execution_time', 180);
        $res->add([
            'station' => $branch->station_id,
            'msg' => App\Http\Controllers\Qserv\QservAPIController::GetTransactions($fromDate, $toDate, $branch->station_id)['msg'],
        ]);

    }

    return $res;

});