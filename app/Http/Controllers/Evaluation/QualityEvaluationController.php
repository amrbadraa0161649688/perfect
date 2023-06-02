<?php

namespace App\Http\Controllers\Evaluation;

use App\Http\Controllers\Controller;
use App\Models\CompanyMenuSerial;
use App\Models\EvaluationFileDt;
use App\Models\EvaluationFileHd;
use App\Models\EvaluationHd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QualityEvaluationController extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $data = request()->all();

        $query = EvaluationFileHd::where('company_group_id', $company->company_group_id)
            ->where('evaluation_category_id', 2);

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


        return view('Evaluations.QualityEvaluation.index', compact('company', 'evaluation_files', 'data',
            'branches'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $evaluation_hds = EvaluationHd::where('company_group_id', $company->company_group_id)
            ->where('evaluation_category_id', 2)->latest()->get();
        return view('Evaluations.QualityEvaluation.create', compact('evaluation_hds'));
    }

    public function createM()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $evaluation_hds = EvaluationHd::where('company_group_id', $company->company_group_id)
            ->where('evaluation_category_id', 2)->latest()->get();
        return view('Evaluations.QualityEvaluation.createM', compact('evaluation_hds'));
    }


    public function store(Request $request)
    {

        DB::beginTransaction();
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $last_bonds_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 158)->latest()->first();

        if (isset($last_bonds_serial)) {
            $last_bonds_serial_no = $last_bonds_serial->serial_last_no;
            $array_number = explode('-', $last_bonds_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_bonds_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'EV-Q-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 158,
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
            'evaluation_category_id' => 2
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
        DB::commit();
        return redirect()->route('quality-evaluation');


    }

    public function storeM(Request $request)
    {

        DB::beginTransaction();
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $last_bonds_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 158)->latest()->first();

        if (isset($last_bonds_serial)) {
            $last_bonds_serial_no = $last_bonds_serial->serial_last_no;
            $array_number = explode('-', $last_bonds_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_bonds_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'EV-Q-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 158,
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
            'evaluation_category_id' => 2
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
        DB::commit();
        return redirect()->route('sales');
    }



    public function show($id)
    {
        $evaluation_file = EvaluationFileHd::find($id);
        $evaluation_file_dts = EvaluationFileDt::where('evaluation_file_id', $id)->get()->groupBy('evaluation_id');

        return view('Evaluations.QualityEvaluation.show', compact('evaluation_file_dts', 'evaluation_file'));

    }

}
