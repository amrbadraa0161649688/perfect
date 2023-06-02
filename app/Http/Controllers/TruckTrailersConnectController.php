<?php

namespace App\Http\Controllers;

use App\Models\AssetsM;
use App\Models\Attachment;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Note;
use App\Models\SystemCode;
use App\Models\Trucks;
use App\Models\TruckToTrailerDt;
use App\Models\TruckToTrailerHd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TruckTrailersConnectController extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $query = TruckToTrailerHd::where('company_group_id', $company->company_group_id);

        $data = request()->all();

        $companies = Company::where('company_group_id', $company->company_group_id)->select('company_id', 'company_name_ar', 'company_name_en')->get();
        $trucks = Trucks::where('company_group_id', $company->company_group_id)->select('truck_id', 'truck_name', 'truck_code')
            ->get();
        $trailers = AssetsM::where('asset_type', SystemCode::where('sys_category_id', 150)->where('company_group_id', $company->company_group_id)
            ->where('system_code', 150003)->first()->system_code_id)->where('asset_status', 1)->get();

        $employees = Employee::where('company_group_id', $company->company_group_id)
            ->select('emp_id', 'emp_name_full_ar')->get();

        if (request()->company_id) {
            $query = $query->whereIn('company_id', request()->company_id);
        }

        if (request()->truck_id) {
            $query = $query->whereIn('truck_id', request()->truck_id);
        }

        if (request()->trailer_id) {
            $query = $query->whereIn('trailer_id', request()->trailer_id);
        }

        if (request()->driver_id) {
            $query = $query->whereIn('driver_id', request()->driver_id);
        }

        if (request()->created_date_from) {
            $query->whereDate('transaction_date', '>=', request()->created_date_from);
        }

        if (request()->created_date_to) {
            $query->whereDate('transaction_date', '<=', request()->created_date_to);
        }

        $truck_to_trailers_hd = $query->paginate();

        return view('TrucksTrailersConnect.index', compact('truck_to_trailers_hd', 'companies', 'trucks',
            'trailers', 'employees', 'data'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $trucks = Trucks::where('company_group_id', $company->company_group_id)->select('truck_id', 'truck_name', 'truck_code')
            ->get();

        $employees = Employee::where('company_group_id', $company->company_group_id)
            ->get();

        $trailers = AssetsM::where('asset_type', SystemCode::where('sys_category_id', 150)->where('company_group_id', $company->company_group_id)
            ->where('system_code', 150003)->first()->system_code_id)->where('asset_status', 1)
            ->whereDoesntHave('truck')->get();

        $items_list = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', 151)->select('system_code_id', 'system_code_name_ar', 'system_code_name_en')
            ->get();
        return view('TrucksTrailersConnect.create', compact('trucks', 'company', 'employees',
            'trailers', 'items_list'));
    }

    public function store(Request $request)
    {
        DB::begintransaction();
        $truck = Trucks::find($request->truck_id);
        $company = session('company') ? session('company') : auth()->user()->comopany;

        if ($request->transaction_type_code) {
            $transaction_type = SystemCode::where('system_code', $request->transaction_type_code)
                ->where('company_group_id', $company->company_group_id)->first();
        }


        $hd = TruckToTrailerHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'transaction_date' => Carbon::now(),
            'truck_id' => $request->truck_id,
            'trailer_id' => $request->trailer_id ? $request->trailer_id : $truck->trucker_id,
            'driver_id' => $request->driver_id ? $request->driver_id : $truck->truck_driver_id,
            'create_user' => auth()->user()->user_id,
            'notes_hd' => $request->notes_hd,
            'transaction_type' => isset($transaction_type) ? $transaction_type->system_code_id : ''
        ]);

        if ($request->transaction_type_code == 152001 && !$request->driver_id) {
            $hd->driver_id = null;
            $hd->save();
        }


        if ($request->trailer_id) {
            $trailer = AssetsM::find($request->trailer_id);
            $truck->trucker_id = $request->trailer_id;
            $truck->trucker_ref_no = $trailer->asset_code;
            $truck->save();
        }

        if ($request->driver_id) {
            $truck->truck_driver_id = $request->driver_id;
            $truck->save();
        }

        $items_list = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', 151)->select('system_code_id', 'system_code_name_ar', 'system_code_name_en')
            ->pluck('system_code_id')->toArray();

        foreach ($items_list as $k => $check_list_id) {
            if ($request->check_list_id) {
                if (in_array($check_list_id, $request->check_list_id)) {
                    TruckToTrailerDt::create([
                        'id_hd' => $hd->id,
                        'check_list_id' => $check_list_id,
                        'check_list_status' => 1,
                        'check_list_notes_dt' => $request->check_list_notes_dt[$k] ? $request->check_list_notes_dt[$k] : '',
                    ]);
                } else {
                    TruckToTrailerDt::create([
                        'id_hd' => $hd->id,
                        'check_list_id' => $check_list_id,
                        'check_list_status' => 0,
                        'check_list_notes_dt' => $request->check_list_notes_dt[$k] ? $request->check_list_notes_dt[$k] : '',
                    ]);
                }
            } else {
                TruckToTrailerDt::create([
                    'id_hd' => $hd->id,
                    'check_list_id' => $check_list_id,
                    'check_list_status' => 0,
                    'check_list_notes_dt' => $request->check_list_notes_dt[$k] ? $request->check_list_notes_dt[$k] : '',
                ]);
            }

        }

        if ($request->image) {
            foreach ($request->image as $image) {
                $this->storePhoto($image, $hd->id);
            }
        }

        DB::commit();
        return redirect()->route('TrucksTrailers');
    }

    public function show($id)
    {
        $truck_to_trailer_hd = TruckToTrailerHd::find($id);

        $attachment_types = SystemCode::where('sys_category_id', 11)->get();
        $attachments = Attachment::where('transaction_id', $truck_to_trailer_hd->id)->where('app_menu_id', 134)
            ->where('attachment_type', '!=', 2)->get();
        $notes = Note::where('transaction_id', $truck_to_trailer_hd->id)->where('app_menu_id', 134)->get();
        $photos_attachments = Attachment::where('transaction_id', $truck_to_trailer_hd->id)->where('app_menu_id', 134)
            ->where('attachment_type', '=', 2)->get();

        return view('TrucksTrailersConnect.show', compact('truck_to_trailer_hd', 'attachment_types',
            'attachments', 'notes', 'photos_attachments'));
    }

    public function getTruckDetails()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $truck = Trucks::where('truck_id', request()->truck_id)->first();
        $trailer = $truck->trailer;
        $driver = $truck->driver;

        $q = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 152);

        if (!$truck->driver) {
            $statuses = $q->whereIn('system_code', [152002])->get();
        } else {
            $statuses = $q->whereIn('system_code', [152001, 152003])->get();
        }

        if ($truck->trailer) {
            return response()->json(['status' => 200, 'data' => $trailer, 'truck' => $truck, 'driver' => $driver, 'statuses' => $statuses]);
        } else {
            return response()->json(['status' => 500, 'truck' => $truck, 'driver' => $driver, 'statuses' => $statuses]);
        }

    }

    public function disConnectTruck()
    {
        $truck = Trucks::where('truck_id', request()->truck_id)->first();
        $truck->trucker_id = null;
        $truck->trucker_ref_no = null;
        $truck->save();
        return response()->json(['status' => 200]);
    }

    public function storePhoto($img, $id)
    {
        $file = $this->getPhoto($img);
        Attachment::create([
            'attachment_name' => 'truck-trailer-connect',
            'attachment_type' => 2,
            'issue_date' => Carbon::now(),
//            'expire_date' => $request->expire_date,
//            'issue_date_hijri' => $request->issue_date_hijri,
//            'expire_date_hijri' => $request->expire_date_hijri,
//            'copy_no' => $request->copy_no,
            'attachment_file_url' => $file,
            'attachment_data' => Carbon::now(),
            'transaction_id' => $id,
            'app_menu_id' => 134,
            'created_user' => auth()->user()->user_id,
        ]);

    }

    public function getPhoto($photo)
    {
        $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path("Files"), $name);

        return $name;
    }
}
