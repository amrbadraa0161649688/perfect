<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeCertificate;
use App\Models\SystemCode;
use Illuminate\Http\Request;

class EmployeeCertificatesController extends Controller
{
    //

    public function create($id)
    {

        $employee = Employee::find($id);
        $countries = SystemCode::where('sys_category_id', 12)->where('company_group_id', auth()->user()->company_group_id)
           ->get();

        return view('EmployeeCertificates.create', compact('employee', 'countries'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'emp_certificate_url' => 'required|file'
        ]);

        $file = $this->getFile($request->emp_certificate_url);
        $emp_certificate = EmployeeCertificate::create([

            'emp_id' => $request->emp_id,
            'emp_certificate_country' => $request->emp_certificate_country,
            'emp_certificate_collage' => $request->emp_certificate_collage,
            'emp_certificate_type' => $request->emp_certificate_type,
            'emp_certificate_duration' => $request->emp_certificate_duration,
            'emp_certificate_start_date' => $request->emp_certificate_start_date,
            'emp_certificate_end_date' => $request->emp_certificate_end_date,
            'emp_certificate_url' => $file,
            'created_user' => auth()->user()->user_id,
        ]);

        return redirect('/employees-add/' . $request->emp_id . '/edit?qr=certificates');

    }

    public function edit($id)
    {

        $countries = SystemCode::where('sys_category_id', 12)->where('company_group_id', auth()->user()->company_group_id)
           ->get();
        $certificate = EmployeeCertificate::find($id);

        return view('EmployeeCertificates.edit', compact('certificate', 'countries'));

    }


    public function update($id, Request $request)
    {
        $emp_certificate = EmployeeCertificate::find($id);

        if ($request->emp_certificate_url) {

            $file = $this->getFile($request->emp_certificate_url);
        }
        $emp_certificate->update([

            'emp_certificate_country' => $request->emp_certificate_country,
            'emp_certificate_collage' => $request->emp_certificate_collage,
            'emp_certificate_type' => $request->emp_certificate_type,
            'emp_certificate_duration' => $request->emp_certificate_duration,
            'emp_certificate_start_date' => $request->emp_certificate_start_date,
            'emp_certificate_end_date' => $request->emp_certificate_end_date,
            'emp_certificate_url' => isset($file) ? $file : $emp_certificate->emp_certificate_url,
            'updated_user' => auth()->user()->user_id,
        ]);

        return redirect('/employees-add/' . $emp_certificate->emp_id . '/edit?qr=certificates')->with('success', 'تم تحديث البيانات');
    }

    public function delete($id)
    {

        $certificate = EmployeeCertificate::find($id);
        $certificate->delete();
        return back()->with('error', 'تم حذف المؤهل الدراسي');

    }

    public function getFile($file)
    {
        $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path("Certificates"), $name);
        return $name;
    }

    public function downloadPdf()
    {
        $filePath = public_path("Certificates/" . request()->name);
        $headers = ['Content-Type: application/pdf'];
        $fileName = time() . '.pdf';

        return response()->download($filePath, $fileName, $headers);
    }
}
