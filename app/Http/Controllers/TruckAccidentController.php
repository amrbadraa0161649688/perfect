<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Files\FilesController;
use App\Models\Attachment;
use App\Models\Bond;
use App\Models\Trucks;
use App\Models\CarRentAccident;
use App\Models\CarRentContract;
use App\Models\CompanyMenuSerial;
use App\Models\Employee;
use App\Models\Note;
use App\Models\SystemCode;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class TruckAccidentController extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $car_accidents = CarRentAccident::where('branch_id', session('branch')['branch_id'])->latest()->get();

        $accident_types = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', 129)->get();

        $accident_statuses = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', 130)->get();

//        شركات التامين
        $companies_insurance = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', 128)->get();

        $contracts = CarRentContract::where('company_group_id', $company->company_group_id)->get();


//        جهه التقدير
        $appreciation_sides = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', 131)->get();
////مباشر الحادث
        $direct_employees = Employee::where('company_group_id', $company->company_group_id)->get();

////معقب الحادث
        $follower_employees = Employee::where('company_group_id', $company->company_group_id)->get();

        $branches = $company->branches;


        if (request()->car_accident_type_id) {
            $query = CarRentAccident::where('company_group_id', $company->company_group_id)
                ->whereIn('car_accident_type_id', request()->car_accident_type_id);
            $car_accidents = $query->get();

            if (request()->car_accident_code) {
                $car_accidents = CarRentAccident::where('company_group_id', $company->company_group_id)
                    ->where('car_accident_code', 'like', '%' . request()->car_accident_code . '%')->get();
            }

            if (request()->car_accident_status) {
                $query = $query->whereIn('car_accident_status', request()->car_accident_status);
                $car_accidents = $query->get();
            }

            if (request()->emp_id) {
                $query = $query->whereIn('emp_id', request()->emp_id);
                $car_accidents = $query->get();
            }

            if (request()->contract_id) {
                $query = $query->whereIn('contract_id', request()->contract_id);
                $car_accidents = $query->get();
            }

            if (request()->car_accident_insurance) {
                $query = $query->whereIn('car_accident_insurance', request()->car_accident_insurance);
                $car_accidents = $query->get();
            }

            if (request()->car_accident_appreciate) {
                $query = $query->whereIn('car_accident_appreciate', request()->car_accident_appreciate);
                $car_accidents = $query->get();
            }

            if (request()->car_accident_date_from) {
                $query = $query->whereDate('car_accident_date', '>=', request()->car_accident_date_from);
                $car_accidents = $query->get();
            }

            if (request()->car_accident_date_to) {
                $query = $query->whereDate('car_accident_date', '<=', request()->car_accident_date_to);
                $car_accidents = $query->get();
            }

            if (request()->contract_number) {
                $query = $query->whereHas('contract', function ($query_s) {
                    $query_s->where('contract_code', 'like', '%' . request()->contract_code . '%');
                });

                $car_accidents = $query->get();
            }

            if (request()->car_accident_follower) {
                $query = $query->whereIn('car_accident_follower', request()->car_accident_follower);
                $car_accidents = $query->get();
            }


            if (request()->plate_number) {
                $query = $query->whereHas('car', function ($query_s) {
                    $query_s->where('full_car_plate', 'like', '%' . request()->plate_number . '%');
                });

                $car_accidents = $query->get();
            }


        }

        return view('TruckAccident.index', compact('car_accidents', 'accident_types', 'accident_statuses',
            'contracts', 'companies_insurance', 'appreciation_sides', 'direct_employees', 'branches', 'follower_employees'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $attachment_types = SystemCode::where('sys_category_id', 11)->where('company_group_id', $company->company_group_id)->get();

        $insurance_companies = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', 128)->get();

        $accident_types = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', 129)->get();


        $accident_status = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', 130)->get();

        // return $accident_status;

        $appreciation_sides = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', 131)->get();

        $direct_accident = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', 133)->get();
            $trucks = Trucks::where('company_id', $company->company_id)
            ->whereHaS('driver')->get();
            $direct_employees = Employee::where('company_group_id', $company->company_group_id)->get();

        
            return view('TruckAccident.create', compact('accident_types', 'accident_status',
                'appreciation_sides', 'insurance_companies', 'direct_accident','attachment_types','trucks','direct_employees'));
       

    }

    public function store(Request $request)
    {
        if ($request->attachment_file_url) {
            $request->validate([
                'attachment_data' => 'required',
                'expire_date_hijri' => 'required',
                'expire_date' => 'required',
                'copy_no' => 'required',
                'issue_date_hijri' => 'required',
                'issue_date' => 'required',
                'attachment_type' => 'required',

            ]);
        }

        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $last_accident_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 47)->latest()->first();

        \DB::begintransaction();

        if (isset($last_accident_serial)) {
            $last_accident_serial_no = $last_accident_serial->serial_last_no;
            $array_number = explode('-', $last_accident_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_accident_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'ACCI-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 47,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $car_accident_type = SystemCode::where('system_code_id', $request->car_accident_type_id)->first();
        $contract = CarRentContract::find($request->contract_id);

        /////طرف ثاني
       // if ($car_accident_type->system_code != 129002) {
         //   $contract->contract_total_add = $contract->contract_total_add + $request->car_accident_amount;
           // $contract->save();
       // }

        $car_accident = CarRentAccident::create([
            'car_accident_code' => $string_number,
            'car_accident_type_id' => $request->car_accident_type_id,
            'car_id' => $request->car_id,
            'branch_id' => $branch->branch_id,
           // 'contract_id' => $request->contract_id,
            'car_accident_date_open' => Carbon::now(),
            'car_accident_status' => $request->car_accident_status,
            'car_accident_status_no' => $request->car_accident_status_no,
            'car_accident_appreciate_status' => $request->car_accident_appreciate_status,
            'car_accident_date' => $request->car_accident_date,
            'car_accident_directly' => $request->car_accident_directly,
            'car_accident_number' => $request->car_accident_number,
            'car_accident_ref' => $request->car_accident_ref,
            'car_accident_appreciate' => $request->car_accident_appreciate,
            'car_accident_amount' => $request->car_accident_amount ? $request->car_accident_amount : 0,
            'car_accident_due' => $request->car_accident_amount ? $request->car_accident_amount : 0,
            'car_accident_notes' => $request->car_accident_notes,
            'created_user' => auth()->user()->user_id,
//            , [car_accident_date_close]
//            , [emp_id]
//            , [car_movement_id]
//            , [car_accident_category]

//            , [car_accident_follower]
//            , [car_accident_insurance]
//            , [car_accident_claim_no]
//            , [car_accident_rec_date]
//            , [car_accident_url_doc]
//            , [car_accident_payment]
//            , [car_accident_due]
        ]);


        if ($request->attachment_file_url) {
            $file = $this->getFile($request->attachment_file_url);
            //  return $request->all();
            Attachment::create([
                'attachment_name' => 'company',
                'attachment_type' => $request->attachment_type,
                'issue_date' => $request->issue_date,
                'expire_date' => $request->expire_date,
                'issue_date_hijri' => $request->issue_date_hijri,
                'expire_date_hijri' => $request->expire_date_hijri,
                'copy_no' => $request->copy_no,
                'attachment_file_url' => $file,
                'attachment_data' => $request->attachment_data,
                'transaction_id' => $car_accident->car_accident_id,
                'app_menu_id' => $request->app_menu_id,
                'created_user' => auth()->user()->user_id,
            ]);
        }

        if ($request->notes_data) {
            $now = new DateTime();

            Note::create([
                'app_menu_id' => $request->app_menu_id,
                'transaction_id' => $car_accident->car_accident_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $request->notes_data,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }

        \DB::commit();

        return redirect()->route('truck-accident.index');

    }

    public function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $car_accident = CarRentAccident::find($id);
        $attachment_types = SystemCode::where('sys_category_id', 11)->where('company_group_id', $company->company_group_id)->get();
        $attachments = Attachment::where('transaction_id', $id)->where('app_menu_id', 47)->get();
        $notes = Note::where('transaction_id', $id)->where('app_menu_id', 47)->get();


        $accident_types = SystemCode::where('company_group_id', $company->company_group_id)->
        where('sys_category_id', 129)->get();

        $accident_statuses = SystemCode::where('company_group_id', $company->company_group_id)->
        where('sys_category_id', 130)->get();

        $appreciation_sides = SystemCode::where('company_group_id', $company->company_group_id)->
        where('sys_category_id', 131)->get();

        $direct_accident = SystemCode::where('company_group_id', $company->company_group_id)->
        where('sys_category_id', 133)->get();

        //معقب الحادث
        $accident_followers = Employee::where('company_group_id', $company->company_group_id)
            ->latest()->get(); ///المعقب

        //        شركات التامين
        $companies_insurance = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', 128)->get();

////مباشر الحادث
        $direct_employees = Employee::where('company_group_id', $company->company_group_id)->get();

        $bonds_capture = Bond::where('bond_ref_no', $car_accident->car_accident_code)->where('bond_type_id', 1)->latest()->get();

        $path = request()->path;

        return view('TruckAccident.edit', compact('car_accident', 'attachment_types',
            'attachments', 'notes', 'accident_types', 'accident_statuses', 'appreciation_sides', 'direct_accident',
            'accident_followers', 'companies_insurance', 'direct_employees', 'path', 'bonds_capture'));
    }

    public function update(Request $request, $id)
    {
        $car_accident = CarRentAccident::find($id);

        if ($request->car_accident_url_doc) {
            $file = $this->getFile($request->car_accident_url_doc);
        }

        $car_accident_type = SystemCode::where('system_code_id', $request->car_accident_type_id)->first();

       

        $car_accident->update([
            'car_accident_type_id' => $request->car_accident_type_id,
            'car_accident_date_close' => $request->car_accident_date_close,
            'car_accident_status' => $request->car_accident_status,
            'car_accident_status_no' => $request->car_accident_status_no,
            'car_accident_appreciate_status' => $request->car_accident_appreciate_status,
            'car_accident_date' => $request->car_accident_date,
            'car_accident_directly' => $request->car_accident_directly,
            'car_accident_number' => $request->car_accident_number,
            'car_accident_appreciate' => $request->car_accident_appreciate,
            'car_accident_amount' => $request->car_accident_amount,
            'car_accident_due' => $request->car_accident_amount,
            'car_accident_notes' => $request->car_accident_notes,
            'updated_user' => auth()->user()->user_id,
            'emp_id' => $request->emp_id,
            'car_accident_follower' => $request->car_accident_follower,
            'car_accident_insurance' => $request->car_accident_insurance,
            'car_accident_url_doc' => isset($file),
            'car_accident_claim_no' => $request->car_accident_claim_no,
            'claim_receive_no' => $request->claim_receive_no,

        ]);


      
            return redirect()->route('truck-accident.index');
        

    }


    public function delete($id)
    {
        $car_accident = CarRentAccident::find($id);
        if ($car_accident->contract) {
            if ($car_accident->accidentType->system_code != 129002) {
                $car_accident->contract->contract_total_add = $car_accident->contract->contract_total_add -
                    $car_accident->car_accident_amount;
                $car_accident->contract->save();
            }
        }
        $car_accident->delete();
        return back()->with(['success' => 'تم الحذف']);
    }


    public function getFile($file)
    {
        $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();;
        $file->move(public_path("Files"), $name);
        return $name;
    }
}
