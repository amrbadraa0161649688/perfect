<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $primaryKey = 'notes_id';

    const CREATED_AT = 'notes_date';

    public $timestamps = false;

    protected $fillable = ['app_menu_id', 'transaction_id', 'notes_serial', 'notes_date', 'notes_data',
        'notes_user_id'];

    public function applicationMenu()
    {
        return $this->belongsTo('App\Models\ApplicationsMenu', 'app_menu_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User','notes_user_id');
    }
}
