<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\Job;
use App\Models\User;
use App\Models\UserBranch;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    public function index()
    {
        return view('Auth.login');
    }

    public function getCompanyGroupWithCompanies()
    {
        $user = User::where('user_mobile', request()->user_mobile)->with('companyGroup')->first();
        if (isset($user)) {
            if ($user->user_type_id == 1) {
                $companies_group = CompanyGroup::get();
                return response()->json(['status' => 200, 'data' => $user, 'companies_group' => $companies_group]);
            } else {
                $company_group = CompanyGroup::where('company_group_id', $user->company_group_id)->first();
//                $company = $user->company;
//                $companies_id = User::where('user_mobile', request()->user_mobile)->pluck('company_id')->toArray();
//                $companies = Company::whereIn('company_id', $companies_id)->where('company_group_id', $company_group->company_group_id)
//                    ->get();
                $branches_id = $user->branches->pluck('branch_id')->toArray();
                $companies_ids = Branch::whereIn('branch_id', $branches_id)->pluck('company_id')->toArray();
                $companies = Company::whereIn('company_id', $companies_ids)->get();
                // $branches = Branch::whereIn('branch_id', $branches_id)->get();

                if ($companies->count() > 1) {
                    return response()->json(['status' => 200, 'data' => $user,
                        'companies' => $companies, 'company_group' => $company_group]);
                } else {
                    $company = $user->company;
                    $branches = $company->branches;

                    if(in_array($user->defaultBranch->branch_id,$branches->pluck('branch_id')->toArray())){
                        $defaultBranch = true;
                    }else{
                        $defaultBranch = false;
                    }

                    return response()->json(['status' => 200, 'data' => $user,
                        'company' => $company, 'company_group' => $company_group, 'branches' => $branches,
                        'defaultBranch' => $defaultBranch]);
                }

            }

        } else {
            return response()->json(['status' => 500, 'data' => 'لا يوجد مستخدم بهذا الرقم']);
        }
    }

    public function getCompanyBranches(Request $request)
    {
        $branches = Branch::where('company_id', $request->company_id)->get();
        return response()->json(['status' => 200, 'branches' => $branches]);
    }


    public function login(Request $request)
    {
        $user = User::where('user_mobile', $request->user_mobile)->first();
        if (isset($user)) {
            if (Hash::check($request->user_password, $user->user_password)) {
                if ($user->user_status_id == 1) {
                    $company = Company::where('company_id', $request->company_id)->first();
                    if ($company->co_is_active == 1) {
//                        $ip = '103.239.147.187'; //For static IP address get
//                        $data = \Location::get($ip);
//
//                        $longitude = $data->longitude;
//                        $latitude = $data->latitude;
//
//                        $earth = 6378.137;  //radius of the earth in kilometer
//                        //$pi = Math . PI;
//                        $pi = 3.14;
//                        $m = (1 / ((2 * 0.018 / 360) * $earth)) / 1000;  //1 meter in degree
//                        $new_latitude = $latitude + (1000 * $m);
//                        $new_longitude = $longitude + (1000 * $m) / cos($latitude * ($pi / 180));
//
                        $branch = Branch::where('branch_id', $request->branch_id)->first();
//                        // $ip= \Request::getClientIp(true)
//                        if ($data->latitude <= $branch->branch_lat && $branch->branch_lat <= $new_latitude && $branch->branch_lng >= $longitude && $branch->branch_lng <= $new_longitude) {
                        $date_now = Carbon::now()->timezone('Africa/Cairo');
                        $login_date = $date_now->format('Y-m-d');

                        $company_start_date = strtotime($company->co_open_date);
                        $company_start_date_new = date("Y-m-d", $company_start_date);

                        $company_end_date = strtotime($company->co_end_date);
                        $company_end_date_new = date("Y-m-d", $company_end_date);

                        if ($company_start_date_new < $login_date && $login_date < $company_end_date_new) {

                            ///// if authenticated user is not admin
//                            $hour_now = Carbon::now()->timezone('Africa/Cairo');
//                            $login_hour = $hour_now->format('H:i');
//                            $user_branch = UserBranch::where('user_id', $user->user_id)->where('branch_id', $request->branch_id)->first();
//                            $branch_start_hour = strtotime($user_branch->start_time);
//                            $branch_start_hour_new = date("H:i", $branch_start_hour);
//
//                            $branch_end_hour = strtotime($user_branch->end_time);
//                            $branch_end_hour_new = date("H:i", $branch_end_hour);
//
//                            if ($login_hour >= $branch_start_hour_new && $login_hour <= $branch_end_hour_new) {
                            $user_log_last = UserLog::where('user_id', $user->user_id)->where('logout_at', null)->latest()->first();

//                            if (isset($user_log_last)) {
//                                return back()->with(['error' => 'يوجد عمليه تسخيل اخري برجاء تسجيل الخروج اولا']);
//                            }

                            $token = Str::random(60);
                            $user->user_token = hash('sha256', $token);
                            $user->save();

                            if (Auth::attempt(['user_mobile' => $request->user_mobile, 'password' => $request->user_password])) {
                                $request->session()->put('branch', $branch);
                                $request->session()->put('company', $company);
                                if ($user->user_type_id != 1) {
                                    $user_branch = UserBranch::where('user_id', $user->user_id)->where('branch_id', $branch->branch_id)
                                        ->first();

                                        if (isset($user_branch)) {
                                        $job = Job::where('job_id', $user_branch->job_id)->first();
                                        if (isset($job)) {
                                            $request->session()->put('job', $job);
                                            if ($user->user_type_id == 498) {
                                                return redirect()->route('sales');
                                            } else {
                                                return redirect()->route('home');
                                            }
                                        } else {
                                            return back()->with(['error' => 'لايوجد وظيفه']);
                                        }
                                    } else {
                                        return back()->with(['error' => 'لايوجد فرع']);
                                    }
                                } else {
                                    $company_group = CompanyGroup::where('company_group_id', $company->company_group_id)->first();
                                    $request->session()->put('company_group', $company_group);
                                    if ($user->user_type_id == 498) {
                                        return redirect()->route('sales');
                                    } else {
                                        return redirect()->route('home');
                                    }
                                }

                            } else {
                                return back()->with(['error' => 'يوجد خطا ']);
                            }

//                                UserLog::create([
//                                    'user_id' => $user->user_id,
//                                    'company_group_id' => $user->company_group_id,
//                                    'company_id' => $branch->company->company_id,
//                                    'login_at' => Carbon::now(),
//                                ]);

//                                $job = Job::where('job_id', UserBranch::where('user_id', $user->user_id)->where('branch_id', $branch->branch_id)->first()->job_id)->first();

//                            } else {
//                                return back()->with(['error' => 'غير مسموح بالدخول في هذا التوقيت']);
//                            }
                        } else {
                            return back()->with(['error' => 'صلاحيه الشركه منتهيه']);
                        }

//                        }
//                        return response()->json(['data' => 'لا يمكن تسجيل الدخول خارج الفرع']);

                    } else {
                        return response()->json(['status' => 500, 'message' => 'الشركه غير مفعله']);
                    }
                } else {
                    return back()->with(['error' => 'الاكونت تم ايقافه']);
                }
            } else {
                return back()->with(['error' => 'كلمه السر غير صحيحه']);
            }
        } else {
            return back()->with(['error' => 'الرقم غير صحيح']);
        }

    }


    ///////////forget password
    public function checkUserMobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_mobile' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => $validator->messages()])->setStatusCode(500);
        }
        $user = User::where('user_mobile', $request->user_mobile)->first();

        if (isset($user)) {
            ////////////send Otp to user here and stored in database
            $user->user_otp = '1234';
            $user->save();
            return response()->json(['data' => $user]);
        } else {
            return response()->json(['data' => 'لا يوجد مستخدم بهذا الرقم']);
        }
    }

    public function checkOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_mobile' => 'required',
            'user_otp' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => $validator->messages()])->setStatusCode(500);
        }
        $user = User::where('user_mobile', $request->user_mobile)->first();
        if ($request->user_otp == $user->user_otp) {
            return response()->json(['data' => 'ال OTP صحيح']);
        } else {
            return response()->json(['data' => 'ال OTP غير صحيح'])->setStatusCode(500);
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_mobile' => 'required',
            'password' => 'min:6|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => $validator->messages()])->setStatusCode(500);
        }

        $user = User::where('user_mobile', $request->user_mobile)->first();
        $user->update(['user_password' => Hash::make($request->password)]);
        $token = Str::random(60);
        $user->user_token = hash('sha256', $token);
        $user->save();
        return response()->json(['data' => $user->user_token]);
    }

    public function logout(Request $request)
    {
//        $user_log = UserLog::where('user_id', request()->user()->user_id)->latest()->first();
//        $user_log->logout_at = Carbon::now();
//        $user_log->save();
//        request()->user()->logout();

        Auth::logout();
        if (session('job')) {
            $request->session()->forget('job');
        }
        if (session('company')) {
            $request->session()->forget('company');
        }

        if (session('company_group')) {
            $request->session()->forget('company_group');
        }
        Session::flush();

        return redirect('/login');
    }

}
