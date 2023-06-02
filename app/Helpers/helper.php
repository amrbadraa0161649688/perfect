<?php

use Illuminate\Support\Facades\File;

if (!function_exists('uploadFile')) {
    function uploadFile($upload, $path, $resize_width = null, $resize_height = null)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $filename = time().rand(11111, 99999) . '.' . $upload->getClientOriginalExtension();
        $upload->move(public_path($path), $filename);
        return $path . '/' . $filename;
    }
}

if (!function_exists('deleteImage')) {
    function deleteImage($path)
    {
        if (file_exists($path)) {
            $delete = File::delete($path);
            if ($delete) return 1;
        }
        return 0;
    }
}


function responseSuccess($data = [], $msg = null, $code = 200)
{
    return response()->json([
        "success" => true,
        "message" => $msg,
        "data" => $data
    ], $code);
}

function responseFail($error_msg = null, $code = 400, $result = null)
{
    return response()->json([
        "message" => $error_msg,
        "errors" => $result,
        "code" => $code
    ], $code);
}

