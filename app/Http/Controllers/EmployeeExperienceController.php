<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeExperience;
use App\Models\SystemCode;
use Illuminate\Http\Request;

class EmployeeExperienceController extends Controller
{
    public function create($id){

        $employee = Employee::find($id);
        $countries = SystemCode::where('sys_category_id', 12)->where('company_group_id', auth()->user()->company_group_id)
            ->get();

        return view('EmployeeExperience.create', compact('employee', 'countries'));

    }

    public function store(Request $request){

        $file = $this->getFile($request->emp_experience_file_url);
        $experience=EmployeeExperience::create([

            'emp_id' => $request->emp_id,
            'emp_experience_job' => $request->emp_experience_job,
            'emp_experience_company' => $request->emp_experience_company,
            'emp_experience_country' => $request->emp_experience_country,
            'emp_experience_period' => $request->emp_experience_period,
            'emp_experience_salary' => $request->emp_experience_salary,
            'emp_experience_leave_reason' => $request->emp_experience_leave_reason,
            'emp_experience_start_date' => $request->emp_experience_start_date,
            'emp_experience_end_date' => $request->emp_experience_end_date,
            'emp_experience_file_url' => $file,
            'created_user' => auth()->user()->user_id,

        ]);

        return redirect('/employees-add/' . $request->emp_id . '/edit?qr=experiences');

    }

    public function edit($id){
        $countries = SystemCode::where('sys_category_id', 12)->where('company_group_id', auth()->user()->company_group_id)
           ->get();
        $experience=EmployeeExperience::find($id);

        return view('EmployeeExperience.edit' ,compact('experience' , 'countries') );


    }

    public function update($id , Request $request){


        $emp_experience=EmployeeExperience::find($id);

        if ($request->emp_experience_file_url) {

            $file = $this->getFile($request->emp_experience_file_url);
        }
        $emp_experience->update

        ([

            'emp_experience_job' => $request->emp_experience_job,
            'emp_experience_company' => $request->emp_experience_company,
            'emp_experience_country' => $request->emp_experience_country,
            'emp_experience_period' => $request->emp_experience_period,
            'emp_experience_salary' => $request->emp_experience_salary,
            'emp_experience_leave_reason' => $request->emp_experience_leave_reason,
            'emp_experience_start_date' => $request->emp_experience_start_date,
            'emp_experience_end_date' => $request->emp_experience_end_date,
            'emp_experience_file_url' => isset($file) ? $file:$emp_experience->emp_experience_file_url ,
            'update_user' => auth()->user()->user_id,

        ]);

        return redirect('/employees-add/' . $emp_experience->emp_id . '/edit?qr=experiences')->with('success', 'تم تحديث البيانات');


    }

    public function delete($id){

        $experience=EmployeeExperience::find($id);

        $experience->delete();

        return back()->with('error', 'تم الحذف');

    }


    public function getFile($file)
    {
        $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path("Experience"), $name);
        return $name;
    }

    public function downloadPdf()
    {
        $filePath = public_path("Experience/" . request()->name);
        $headers = ['Content-Type: application/pdf'];
        $fileName = time() . '.pdf';

        return response()->download($filePath, $fileName, $headers);
    }
}
