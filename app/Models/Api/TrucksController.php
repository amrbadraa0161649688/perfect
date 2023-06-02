<?php

namespace App\Models\Api;

use App\Models\Trucks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrucksController extends Model
{
    use HasFactory;

    public function index()
    {
        $trucks = Trucks::where('company_id', request()->company_id)->get();
        return response()->json(['data', $trucks]);
    }
}
