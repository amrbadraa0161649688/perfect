<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserBranchesResource;
use App\Models\Branch;
use App\Models\Job;
use App\Models\User;
use App\Models\UserBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserBranchesController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'job_id' => 'required',
            'branch_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'user_branch_is_defaul' => 'required',
        ]);

//        $user_branches=UserBranch::where('user_id',$request->user_id)->pluck('user_branch_is_defaul')->toArray();
//        if($user_branches->count() > 0 && in_array(1,$user_branches)){
//            return response()->json(['data' => 'يوجد فرع رئيسي لهذا المستخدم']);

        $user_branch_old = UserBranch::where('user_id', $request->user_id)->where('branch_id', $request->branch_id)->first();
        if (isset($user_branch_old)) {
            return back()->with(['error' => 'تم اضافه الفرع من قبل']);
        }

        $user_branch = UserBranch::create([
            'user_id' => $request->user_id,
            'company_id' => $request->company_id,
            'job_id' => $request->job_id,
            'branch_id' => $request->branch_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'user_branch_is_defaul' => $request->user_branch_is_defaul,
        ]);

        if ($request->user_branch_is_defaul == 1) {
            $user = User::find($request->user_id);
            $user->update(['user_default_branch_id' => $request->branch_id]);
        }

//        return response()->json(['status' => 200, 'data' => new UserBranchesResource($user_branch)]);
        return back()->with(['success', 'تم اضافه فرع للمستخدم']);
    }

    public function edit($user_id, $branch_id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $userBranch = UserBranch::where('branch_id', $branch_id)->where('user_id', $user_id)->first();
        $branch_all = Branch::where('company_group_id', $company->company_group_id)->get();
        $user = User::find($user_id);
        return view('users.userBranches.edit', compact('userBranch', 'user','branch_all'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'user_id' => 'required',
            'job_id' => 'required',
            'branch_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'user_branch_is_defaul' => 'required',
        ]);

        $user = User::find($request->user_id);
        $branch = Branch::find($id);

        if ($branch->branch_id != $request->branch_id) {
            $user_branch_old = UserBranch::where('user_id', $request->user_id)->where('branch_id', $request->branch_id)->first();
            if (isset($user_branch_old)) {
                return back()->withErrors(['msg' => 'تم اضافه الفرع من قبل']);
            }
        }

        $user->branches()->detach($branch->branch_id);
        $user->branches()->attach($request->branch_id, [
            'job_id' => $request->job_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'user_branch_is_defaul' => $request->user_branch_is_defaul
        ]);


        if ($request->user_branch_is_defaul == 1) {
            $user->update(['user_default_branch_id' => $request->branch_id]);
        }

        return redirect()->route('user.edit', $user->user_id)->with(['success', 'تم تعديل الفرع']);
    }


    public function showJob($id)
    {
        $job = Job::find($id);
        return response()->json(['status' => 200, 'data' => $job->permissions]);
    }
}
