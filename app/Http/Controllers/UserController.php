<?php

namespace App\Http\Controllers;

use App\Http\Middleware\UsersApp\Add;
use App\Http\Resources\UserJobResource;
use App\Http\Resources\UserResource;
use App\Models\Attachment;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\Job;
use App\Models\Note;
use App\Models\SystemCode;
use App\Models\User;
use App\Models\UserBranch;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function getCompanyList()
    {
        $user_branch = UserBranch::where('user_id', request()->user_id)->first();
        $companies = Company::where('company_group_id', request()->company_group_id)->get();
        if (isset($user_branch)) {
            return response()->json(['status' => 200, 'data' => $user_branch->company]);
        } else {
            return response()->json(['status' => 200, 'data' => $companies]);
        }
    }

    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        if (request()->company_id) {
            $company = Company::find(request()->company_id);
            $companies = Company::where('company_group_id', $company->company_group_id)->get();
        }
        if ($request->ajax()) {
            $data = User::where('company_group_id', $company->company_group_id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return (string)view('Users.Actions.actions', compact('row'));
                })
                ->addColumn('photo', function ($row) {
                    return view('Users.Actions.photo', compact('row'));
                })
                ->addColumn('company', function ($row) {
                    return (string)view('Users.Actions.company', compact('row'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_email' => 'unique:users',
            'user_profile_url' => 'required',
        ]);
//      return $request->all();
        $user_old = User::where('user_code', $request->user_code)->first();
        if (isset($user_old)) {
            return back()->with(['error' => 'هذا الكود تم ادخاله من قبل']);
        }
        $profile = $this->getPhoto($request->user_profile_url);
        $company = session('company') ? session('company') : auth()->user()->company;

        User::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'user_code' => $request->user_code,
            'user_password' => Hash::make($request->user_password),
            'user_email' => $request->user_email,
            'user_name_ar' => $request->user_name_ar,
            'user_name_en' => $request->user_name_en,
            'user_profile_url' => 'Users/' . $profile,
            'user_mobile' => $request->user_mobile,
            'user_start_date' => $request->user_start_date,
            'user_end_date' => $request->user_end_date,
            'user_type_id' => 2,
            'user_status_id' => 1
        ]);

        return redirect()->route('users')->with('success', 'تم اضافه المستخدم');
    }

    public function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch_all = Branch::where('company_group_id', $company->company_group_id)->get();
        $user = User::where('user_id', $id)->first();
        $attachments = Attachment::where('transaction_id', $user->user_id)->where('app_menu_id', 25)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)->where('company_group_id', $company->company_group_id)
            ->where('company_id', $company->company_id)->get();
        $notes = Note::where('transaction_id', $user->user_id)->where('app_menu_id', 25)->get();
        return view('users.edit', compact('user', 'attachments', 'attachment_types', 'notes', 'company','branch_all'));
    }

    public function update($id, Request $request)
    {
        $user = User::where('user_id', $id)->first();

        if ($request->user_code != $user->user_code) {
            $user_old = User::where('user_code', $request->user_code)->first();
            if (isset($user_old)) {
                return back()->with(['error' => 'هذا الكود تم ادخاله من قبل']);
            }
        }

        if ($request->user_profile_url) {
            $profile = $this->getPhoto($request->user_profile_url);
        }

        $user->update([
            'user_code' => $request->user_code,
            'user_password' => isset($request->user_password) ? Hash::make($request->user_password) : $user->user_password,
            'user_email' => $request->user_email,
            'user_name_ar' => $request->user_name_ar,
            'user_name_en' => $request->user_name_en,
            'user_profile_url' => isset($profile) ? 'Users/' . $profile : $user->user_profile_url,
            'user_mobile' => $request->user_mobile,
            'user_status_id' => $request->user_status_id ? $request->user_status_id : $user->user_status_id,
            'user_start_date' => $request->user_start_date,
            'user_end_date' => $request->user_end_date,
        ]);
        return redirect()->route('users')->with('success', 'تم تعديل بيانات المستخدم');
    }

    public function getPhoto($photo)
    {
        $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path("Users"), $name);
        return $name;
    }
}
