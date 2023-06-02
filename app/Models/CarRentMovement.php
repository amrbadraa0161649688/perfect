<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRentMovement extends Model
{
    use HasFactory;

    protected $table = 'car_rent_movement';
    protected $primaryKey = 'car_movement_id';

    protected $fillable = [
        'car_movement_code', 'car_movement_type_id', 'car_id', 'car_movement_start', 'car_movement_end',
        'car_movement_user_open', 'car_movement_user_close', 'car_movement_branch_open', 'car_movement_branch_close',
        'start_kilomaters', 'close_kilomaters', 'total_kilomaters', 'car_movement_notes_open', 'car_movement_notes_close',
        'car_movement_driver_id', 'follow_up_days_start', 'follow_up_days_end', 'follow_up_days_date', 'shipping_company_name',
        'shipping_company_no', 'shipping_company_date','company_group_id','company_id'
    ];


    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SystemCode::class, 'car_movement_type_id');
    }

    public function car(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CarRentCars::class, 'car_id');
    }

    public function userOpen(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'car_movement_user_open');
    }

    public function userClose(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'car_movement_user_close');
    }

    public function branchOpen(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class, 'car_movement_branch_open');
    }

    public function branchClose(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class, 'car_movement_branch_close');
    }

    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class, 'car_movement_driver_id');
    }
}
