<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Company;
use App\Models\Branch;
use App\Models\SystemCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FilesController extends Controller
{
    public function store(Request $request)
    {

        $file = $request->hasFile('attachment_file_url')
            ? $this->getFile($request->attachment_file_url) : null;
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
            'transaction_id' => $request->transaction_id,
            'app_menu_id' => $request->app_menu_id,
            'created_user' => auth()->user()->user_id,
        ]);

        return back()->with(['success' => 'تم اضافه المستند']);
    }

    public function edit($id)
    {
        $attachments = Attachment::where('attachment_id', $id)->where('app_menu_id', 8)->get();

        $company = session('company') ? session('company') : auth()->user()->company;

        $branches = Branch::where('company_id', $id)->latest()->get();

        $attachment_types = SystemCode::where('sys_category_id', 11)
            ->where('company_group_id', $company->company_group_id)->get();

        return view('components.files.edit', compact('attachments', 'branches',
            'attachment_types'));
    }

    public function update(Request $request, $id)
    {
        $Attachment = Attachment::where('attachment_id', $id);
        //  $file = $this->getFile($request->attachment_file_url);
        //  return $request->all();
        $Attachment->update([
            //  'attachment_name' => 'company',
            'attachment_type' => $request->attachment_type,
            'issue_date' => $request->issue_date,
            'expire_date' => $request->expire_date,
            'issue_date_hijri' => $request->issue_date_hijri,
            'expire_date_hijri' => $request->expire_date_hijri,
            'copy_no' => $request->copy_no,
            // 'attachment_file_url' => $file,
            'attachment_data' => $request->attachment_data,
            //  'transaction_id' => $request->transaction_id,
            //  'app_menu_id' => $request->app_menu_id,
            'created_user' => auth()->user()->user_id,
        ]);
        return redirect()->route('employees')->with(['success' => 'تم تحديث بيانات المستند']);
        //   return back()->with(['success' => 'تم تعديل المستند']);
    }


    public function getFile($file)
    {
        $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();;
        $file->move(public_path("Files"), $name);
        return $name;
    }

    public function downloadPdf()
    {
        $extension = Str::contains(request()->name, '.pdf');
        if ($extension) {
            $filePath = public_path("Files/" . request()->name);
            $headers = ['Content-Type: application/pdf'];
            $fileName = time() . '.pdf';

            return response()->download($filePath, $fileName, $headers);
        } else {
            return back()->with(['error' => 'الملف ليس pdf']);
        }

    }
}
