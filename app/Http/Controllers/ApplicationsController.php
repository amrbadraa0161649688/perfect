<?php

namespace App\Http\Controllers;

use App\Http\Resources\BasicMenuResource;

//use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ApplicationsController extends Controller
{
    public function index()
    {
        try {
            if (auth()->user()->user_type_id == 1) {
                $applications = Application::get();
                //$applications = auth()->company->apps;
            } else {
                $applications = auth()->user()->company->apps;
            }
            return view('Applications.index', compact('applications'));
        } catch (\Exception $e) {
            abort(404);
        }

    }

    public function store(Request $request)
    {
        try {
            Application::create([
                'app_name_ar' => $request->app_name_ar,
                'app_name_en' => $request->app_name_en,
                'app_code' => $request->app_code,
                'app_status' => $request->app_status,
                'app_icon' => $request->app_icon
            ]);

            return back()->with('success', 'تم اضافه النظام ');
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function show($id)
    {
        $application = Application::where('app_id', $id)->with('applicationMenu')->first();
        return response()->json(['status' => 200, 'data' => $application]);
    }

    public function update(Request $request, $id)
    {
        $application = Application::where('app_id', $id)->first();
        $application->update([
            'app_name_ar' => $request->app_name_ar,
            'app_name_en' => $request->app_name_en
//            'app_code' => $request->app_code,
//            'app_icon' => $request->app_icon
        ]);
        return back()->with('success', 'تم تعديل النظام ');;

    }


    public function basicMenu()
    {
        $applications = Application::get();
        return response()->json(['status' => 200, 'data' => BasicMenuResource::collection($applications)]);
    }

}
