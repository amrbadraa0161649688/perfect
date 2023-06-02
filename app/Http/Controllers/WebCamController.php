<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebCamController extends Controller
{
    public function store(Request $request)
    {
//        $img = $request->image;
//        $folderPath = "Uploads/";
//
//        $image_parts = explode(";base64,", $img);
//        $image_type_aux = explode("image/", $image_parts[0]);
//        $image_type = $image_type_aux[1];
//
//        $image_base64 = base64_decode($image_parts[1]);
//        $fileName = uniqid() . '.png';
//
//        $file = $folderPath . $fileName;
//
//        Storage::put($file, $image_base64);


        $img = $request->image;
        $folderPath = "Uploads/"; //path location

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $uniqid = uniqid();
        $file = $folderPath . $uniqid . '.' . $image_type;
        file_put_contents($file, $image_base64);

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
            'transaction_id' => $request->transaction_id,
            'app_menu_id' => $request->app_menu_id,
            'created_user' => auth()->user()->user_id,
        ]);

        return back();
    }

    public function getPhoto(Request $request)
    {
        $photo = $request->image;
        $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path("Uploads"), $name);
        Attachment::create([
            'attachment_name' => 'company',
            'attachment_type' => 2,
            'issue_date' => Carbon::now(),
//            'expire_date' => $request->expire_date,
//            'issue_date_hijri' => $request->issue_date_hijri,
//            'expire_date_hijri' => $request->expire_date_hijri,
//            'copy_no' => $request->copy_no,
            'attachment_file_url' => $name,
            'attachment_data' => Carbon::now(),
            'transaction_id' => $request->evaluation_file_id,
            'app_menu_id' => 157,
            'created_user' => auth()->user()->user_id,
        ]);
    }
}
