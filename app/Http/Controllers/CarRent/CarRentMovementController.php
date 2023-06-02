<?php

namespace App\Http\Controllers\CarRent;

use App\Enums\EnumSetting;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarRent\Movement\MovementRequest;
use App\Models\Branch;
use App\Models\CarRentBrand;
use App\Models\CarRentCars;
use App\Models\CarRentMovement;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\Employee;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CarRentMovementController extends Controller
{
    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branches = Branch::where('company_group_id', $company->company_group_id)->get();
        $brands = CarRentBrand::where('company_group_id', $company->company_group_id)->get();
        $types = SystemCode::where('sys_category_id', 123)->where('system_code_filter', 'movements')
            ->where('company_group_id', $company->company_group_id)->get();

        $drivers = Employee::where('company_group_id', $company->company_group_id)
            ->where('emp_status', SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 23)->first()->system_code_id)->get();

        $records = CarRentMovement::where('company_group_id', $company->company_group_id);
        $movement_open_count = CarRentMovement::where('company_group_id', $company->company_group_id);
        $movement_close_count = CarRentMovement::where('company_group_id', $company->company_group_id);

        if ($request->car_movement_driver_id) {
            $records->whereIn('car_movement_driver_id', $request->car_movement_driver_id);

            $movement_open_count->whereIn('car_movement_driver_id', $request->car_movement_driver_id);
            $movement_close_count->whereIn('car_movement_driver_id', $request->car_movement_driver_id);
        }
        if ($request->brand_ids) {
            $records->whereHas('car', function ($q) use ($request) {
                $q->whereIn('car_brand_id', $request->brand_ids);
            });

            $movement_open_count->whereHas('car', function ($q) use ($request) {
                $q->whereIn('car_brand_id', $request->brand_ids);
            });
            $movement_close_count->whereHas('car', function ($q) use ($request) {
                $q->whereIn('car_brand_id', $request->brand_ids);
            });
        }
        if ($request->car_plate) {
            $records->whereHas('car', function ($q) use ($request) {
                $q->where('full_car_plate', 'like', '%' . $request->car_plate . '%');
            });

            $movement_open_count->whereHas('car', function ($q) use ($request) {
                $q->where('full_car_plate', 'like', '%' . $request->car_plate . '%');
            });
            $movement_close_count->whereHas('car', function ($q) use ($request) {
                $q->where('full_car_plate', 'like', '%' . $request->car_plate . '%');
            });
        }
        if ($request->car_movement_type_id) {
            $records->whereIn('car_movement_type_id', $request->car_movement_type_id);

            $movement_open_count->whereIn('car_movement_type_id', $request->car_movement_type_id);
            $movement_close_count->whereIn('car_movement_type_id', $request->car_movement_type_id);
        }
        if ($request->from_car_movement_start) {
            $records->whereDate('car_movement_start', '>=', $request->from_car_movement_start);

            $movement_open_count->whereDate('car_movement_start', '>=', $request->from_car_movement_start);
            $movement_close_count->whereDate('car_movement_start', '>=', $request->from_car_movement_start);
        }
        if ($request->to_car_movement_start) {
            $records->whereDate('car_movement_start', '<=', $request->to_car_movement_start);

            $movement_open_count->whereDate('car_movement_start', '<=', $request->to_car_movement_start);
            $movement_close_count->whereDate('car_movement_start', '<=', $request->to_car_movement_start);
        }
        if ($request->from_car_movement_start) {
            $records->whereDate('car_movement_end', '>=', $request->from_car_movement_end);

            $movement_open_count->whereDate('car_movement_end', '>=', $request->from_car_movement_end);
            $movement_close_count->whereDate('car_movement_end', '>=', $request->from_car_movement_end);
        }
        if ($request->to_car_movement_start) {
            $records->whereDate('car_movement_end', '<=', $request->to_car_movement_end);

            $movement_open_count->whereDate('car_movement_end', '<=', $request->to_car_movement_end);
            $movement_close_count->whereDate('car_movement_end', '<=', $request->to_car_movement_end);
        }
        if ($request->car_movement_branch_opens) {
            $records->whereIn('car_movement_branch_open', $request->car_movement_branch_opens);

            $movement_open_count->whereIn('car_movement_branch_open', $request->car_movement_branch_opens);
            $movement_close_count->whereIn('car_movement_branch_open', $request->car_movement_branch_opens);
        }
        if ($request->car_movement_branch_close) {
            $records->whereIn('car_movement_branch_close', $request->car_movement_branch_close);

            $movement_open_count->whereIn('car_movement_branch_close', $request->car_movement_branch_close);
            $movement_close_count->whereIn('car_movement_branch_close', $request->car_movement_branch_close);
        }
        if ($request->car_movement_end == 'open') {
            $records->whereNull('car_movement_end');

            $movement_open_count->whereNull('car_movement_end');
            $movement_close_count->whereNull('car_movement_end');
        }
        if ($request->car_movement_end == 'close') {
            $records->whereNotNull('car_movement_end');

            $movement_open_count->whereNotNull('car_movement_end');
            $movement_close_count->whereNotNull('car_movement_end');
        }
        $records = $records->latest()->paginate(EnumSetting::Paginate);

        $movement_open_count = $movement_open_count->whereNull('car_movement_end')->count();
        $movement_close_count = $movement_close_count->whereNotNull('car_movement_end')->count();
        return view('CarRent.Movements.index', compact('records', 'companies',
            'branches', 'brands', 'types', 'drivers', 'movement_close_count', 'movement_open_count'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch') ? session('branch') : auth()->user()->defaultBranch;
        $ready_car = SystemCode::where('company_group_id', $company->company_group_id)->whereSystemCode(123001)->first();
        $branches = Branch::where('company_group_id', $company->company_group_id)->get();
        $cars = CarRentCars::where('company_group_id', $company->company_group_id)->where('branch_id', $branch->branch_id)
            ->where('car_status_id', $ready_car->system_code_id)
            ->with('brand', 'brandDetails')->get();

        $drivers = Employee::where('company_group_id', $company->company_group_id)
            ->where('emp_status', SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 23)->first()->system_code_id)->get();

        $types = SystemCode::where('sys_category_id', 123)->where('system_code_filter', 'movements')
            ->where('company_group_id', $company->company_group_id)->get();
        return view('CarRent.Movements.create', compact('branch', 'branches', 'cars', 'drivers', 'types'));
    }

    public function store(MovementRequest $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch') ? session('branch') : auth()->user()->defaultBranch;

        $last_car_movement_serial = CompanyMenuSerial::where('company_id', $company->company_id)
            ->where('app_menu_id', 45)->latest()->first(); // edit app menu id

        if (isset($last_car_movement_serial)) {
            $last_car_movement_serial_no = $last_car_movement_serial->serial_last_no;
            $array_number = explode('-', $last_car_movement_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_car_movement_serial->update(['serial_last_no' => $string_number]);

        } else {
            $string_number = 'M-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 45,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id,
            ]);
        }

        $record = CarRentMovement::create($request->except('_token', '_method') + [
                'car_movement_code' => $string_number,
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'car_movement_branch_open' => $branch->branch_id,
                'car_movement_user_open' => auth()->id(),
                'car_movement_start' => now()
            ]);
        $record->car()->update([
            'car_status_id' => $request->car_movement_type_id
        ]);
        return redirect(route('movements.index'));
    }

    public function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch') ? session('branch') : auth()->user()->defaultBranch;
        $ready_car = SystemCode::where('company_group_id', $company->company_group_id)->whereSystemCode(123001)->first();
        $cars = CarRentCars::where('company_group_id', $company->company_group_id)->where('branch_id', $branch->branch_id)
            ->where('car_status_id', $ready_car->system_code_id)->get();

        $drivers = Employee::where('company_group_id', $company->company_group_id)
            ->where('emp_status', SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 23)->first()->system_code_id)->get();

        $types = SystemCode::where('sys_category_id', 123)->where('system_code_filter', 'movements')
            ->where('company_group_id', $company->company_group_id)->get();
        $record = CarRentMovement::findOrFail($id);
        return view('CarRent.Movements.edit', compact('record', 'cars', 'drivers', 'types'));
    }

    public function update(MovementRequest $request, $id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $ready_car = SystemCode::where('company_group_id', $company->company_group_id)->whereSystemCode(123001)->first();
        $record = CarRentMovement::findOrFail($id);
        $days = Carbon::now()->diffInDays($record->car_movement_start);
        $record->update([
            'car_movement_user_close' => auth()->id(),
            'car_movement_end' => now(),
            'close_kilomaters' => $request->close_kilomaters,
            'total_kilomaters' => $request->close_kilomaters - $request->start_kilomaters,
            'car_movement_notes_close' => $request->car_movement_notes_close,
            'follow_up_days_end' => $days ? $days : 1,
        ]);
        $record->car()->update([
            'car_status_id' => $ready_car->system_code_id,
            'branch_id' => $record->car_movement_branch_close
        ]);
        return redirect()->route('movements.index');
    }
}
