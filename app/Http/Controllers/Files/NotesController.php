<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Models\Note;
use DateTime;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function store(Request $request)
    {
        $now = new DateTime();

        Note::create([
            'app_menu_id' => $request->app_menu_id,
            'transaction_id' => $request->transaction_id,
            'notes_serial' => rand(11111, 99999),
            'notes_data' => $request->notes_data,
            'notes_date' => $now->format('Y-m-d'),
            'notes_user_id' => auth()->user()->user_id
        ]);

        return back();

    }
}
