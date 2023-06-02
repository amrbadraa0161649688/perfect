<?php

namespace App\Http\Controllers\Evaluation;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\CompanyMenuSerial;
use App\Models\EvaluationDt;
use App\Models\EvaluationFileDt;
use App\Models\EvaluationFileHd;
use App\Models\EvaluationHd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalInspectionController extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $data = request()->all();

        $query = EvaluationFileHd::where('company_group_id', $company->company_group_id)
            ->where('evaluation_category_id', 1);

        if (request()->branch_id) {
            $query = $query->whereIn('branch_id', request()->branch_id);
        }

        if (request()->date_from) {
            $query = $query->whereDate('created_at', '>=', request()->date_from);
        }

        if (request()->date_to) {
            $query = $query->whereDate('created_at', '<=', request()->date_from);
        }

        $evaluation_files = $query->latest()->paginate();

        $branches = $company->branches;


        return view('Evaluations.internalInspection.index', compact('company', 'evaluation_files', 'data',
            'branches'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $evaluation_hds = EvaluationHd::where('company_group_id', $company->company_group_id)
            ->where('evaluation_category_id', 1)->latest()->get();
        return view('Evaluations.internalInspection.create', compact('evaluation_hds'));
    }

    public function createM()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $evaluation_hds = EvaluationHd::where('company_group_id', $company->company_group_id)
            ->where('evaluation_category_id', 1)->latest()->get();
        return view('Evaluations.internalInspection.createM', compact('evaluation_hds'));
    }


    public function store(Request $request)
    {

        DB::beginTransaction();
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $last_bonds_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 157)->latest()->first();

        if (isset($last_bonds_serial)) {
            $last_bonds_serial_no = $last_bonds_serial->serial_last_no;
            $array_number = explode('-', $last_bonds_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_bonds_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'EV-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 157,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id,
            ]);
        }

        $evaluation_file_hd = EvaluationFileHd::create([
            'evaluation_file_code' => $string_number,
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'evaluation_file_date' => Carbon::now(),
            'created_by' => auth()->user()->user_id,
            'evaluation_category_id' => 1
        ]);


        foreach ($request->evaluation_result as $k => $evaluation_result) {
            $evaluation_file_dt = EvaluationFileDt::where('evaluation_file_id ', $evaluation_file_hd->evaluation_file_id)
                ->where('evaluation_id', $request->evaluation_id[$k])
                ->first();

            if (isset($evaluation_file_dt)) {
                $evaluation_dt_serial = $evaluation_file_dt->evaluation_dt_serial + 1;
            } else {
                $evaluation_dt_serial = 1;
            }

            EvaluationFileDt::create([
                'evaluation_file_id' => $evaluation_file_hd->evaluation_file_id,
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'evaluation_id' => $request->evaluation_id[$k],
                'created_by' => auth()->user()->user_id,
                'evaluation_notes' => isset($request->evaluation_notes[$k]) ? $request->evaluation_notes[$k] : '',
                'evaluation_result' => $evaluation_result,
                'evaluation_dt_id' => $request->evaluation_dt_id[$k],
                'evaluation_dt_serial' => $evaluation_dt_serial
            ]);
        }

        foreach($request->image as $image){
            $img = $image;
            $file = $this->getPhoto($img);

            Attachment::create([
                'attachment_name' => 'company',
                'attachment_type' => 2,
                'issue_date' => Carbon::now(),
//            'expire_date' => $request->expire_date,
//            'issue_date_hijri' => $request->issue_date_hijri,
//            'expire_date_hijri' => $request->expire_date_hijri,
//            'copy_no' => $request->copy_no,
                'attachment_file_url' => $file,
                'attachment_data' => Carbon::now(),
                'transaction_id' => $evaluation_file_hd->evaluation_file_id,
                'app_menu_id' => 157,
                'created_user' => auth()->user()->user_id,
            ]);
        }


        DB::commit();
        return redirect()->route('internal-inspection');

    }


    public function storeM(Request $request)
    {

        DB::beginTransaction();
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $last_bonds_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 157)->latest()->first();

        if (isset($last_bonds_serial)) {
            $last_bonds_serial_no = $last_bonds_serial->serial_last_no;
            $array_number = explode('-', $last_bonds_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_bonds_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'EV-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 157,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id,
            ]);
        }

        $evaluation_file_hd = EvaluationFileHd::create([
            'evaluation_file_code' => $string_number,
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'evaluation_file_date' => Carbon::now(),
            'created_by' => auth()->user()->user_id,
            'evaluation_category_id' => 1
        ]);


        foreach ($request->evaluation_result as $k => $evaluation_result) {
            $evaluation_file_dt = EvaluationFileDt::where('evaluation_file_id ', $evaluation_file_hd->evaluation_file_id)
                ->where('evaluation_id', $request->evaluation_id[$k])
                ->first();

            if (isset($evaluation_file_dt)) {
                $evaluation_dt_serial = $evaluation_file_dt->evaluation_dt_serial + 1;
            } else {
                $evaluation_dt_serial = 1;
            }

            EvaluationFileDt::create([
                'evaluation_file_id' => $evaluation_file_hd->evaluation_file_id,
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'evaluation_id' => $request->evaluation_id[$k],
                'created_by' => auth()->user()->user_id,
                'evaluation_notes' => isset($request->evaluation_notes[$k]) ? $request->evaluation_notes[$k] : '',
                'evaluation_result' => $evaluation_result,
                'evaluation_dt_id' => $request->evaluation_dt_id[$k],
                'evaluation_dt_serial' => $evaluation_dt_serial
            ]);
        }

        foreach($request->image as $image){
            $img = $image;
            $file = $this->getPhoto($img);

            Attachment::create([
                'attachment_name' => 'company',
                'attachment_type' => 2,
                'issue_date' => Carbon::now(),
//            'expire_date' => $request->expire_date,
//            'issue_date_hijri' => $request->issue_date_hijri,
//            'expire_date_hijri' => $request->expire_date_hijri,
//            'copy_no' => $request->copy_no,
                'attachment_file_url' => $file,
                'attachment_data' => Carbon::now(),
                'transaction_id' => $evaluation_file_hd->evaluation_file_id,
                'app_menu_id' => 157,
                'created_user' => auth()->user()->user_id,
            ]);
        }


        DB::commit();
        return redirect()->route('sales');

    }


    
    public function show($id)
    {
        $evaluation_file = EvaluationFileHd::find($id);
        $evaluation_file_dts = EvaluationFileDt::where('evaluation_file_id', $id)->get()->groupBy('evaluation_id');
        $attachments = Attachment::where('app_menu_id', 157)->where('transaction_id', $evaluation_file->evaluation_file_id)->get();
        return view('Evaluations.internalInspection.show', compact('evaluation_file_dts', 'evaluation_file', 'attachments'));

    }

    public function getPhoto($photo)
    {
        $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path("Uploads"), $name);
        return $name;
    }

}
