<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationMenuResource;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\ApplicationsMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApplicationMenuController extends Controller
{
    public function index($id)
    {
        $application_menu = ApplicationsMenu::where('app_id', $id)->get();
        return response()->json(['status' => 200, 'data' => $application_menu]);
    }

    public function create($id)
    {
        $application = Application::where('app_id', $id)->first();
        return view('ApplicationMenu.create', compact('application'));
    }

    public function store(Request $request)
    {

        ApplicationsMenu::create([
            'app_id' => $request->app_id,
            'app_menu_name_ar' => $request->app_menu_name_ar,
            'app_menu_name_en' => $request->app_menu_name_en,
            'app_menu_order' => $request->app_menu_order,
            'app_menu_code' => $request->app_menu_code,
            'app_menu_icon' => $request->app_menu_icon,
            'app_menu_is_active' => $request->app_menu_is_active,

        ]);

        return redirect()->route('applications', $request->app_id)->with('success', 'تم اضافه النظام الفرعي ');

    }

    public function show($id)
    {
        $application_menu = ApplicationsMenu::find($id);
        return view('ApplicationMenu.edit', compact('application_menu'));
    }

    public function update($id, Request $request)
    {
        $application_menu = ApplicationsMenu::find($id);
        $application_menu->update([
            'app_menu_order' => $request->app_menu_order,
            'app_menu_code' => $request->app_menu_code,
            'app_menu_name_ar' => $request->app_menu_name_ar,
            'app_menu_name_en' => $request->app_menu_name_en,
            'app_menu_icon' => $request->app_menu_icon,
            'app_menu_is_active' => $request->app_menu_is_active,
        ]);

        return redirect()->route('applications')->with('success', 'تم تعديل النظام الفرعي ');
    }

}
