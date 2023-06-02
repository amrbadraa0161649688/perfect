<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTree extends Model
{
    use HasFactory;

    protected $table = 'v_account_tree';

    protected $guarded = [];
}
