<?php

namespace App\Repositories\Eloquent;

use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\WaybillDt;
use App\Models\WaybillHd;
use App\Repositories\Interfaces\WayBillRepositoryInterface;
use Illuminate\Support\Carbon;

class WayBillRepository extends BaseRepository implements WayBillRepositoryInterface
{
    public function __construct(WaybillHd $model)
    {
        parent::__construct($model);
    }

    public function create($data)
    {
        $last_waypill_serial = CompanyMenuSerial::where('company_id', $data['company_id'])
            ->where('app_menu_id', 70)->latest()->first();
        if (isset($last_waypill_serial)) {
            $last_waypill_serial_no = $last_waypill_serial->serial_last_no;
            $array_number = explode('-', $last_waypill_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_waypill_serial->update(['serial_last_no' => $string_number]);

        } else {
            $string_number = 'WAY-' . $data['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $data['company_group_id'],
                'company_id' => $data['company_id'],
                'app_menu_id' => 70,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }
        $details_data = $data['details_data'];
        $details_fees_data = $data['details_fees_data'];
        $data['waybill_code'] = $string_number;
        unset($data['details_data']);
        unset($data['details_fees_data']);

        $model = $this->model::create($data);
        $model->Wdetails()->create($details_data);
        if ($details_fees_data) {
            $model->Wdetails()->create($details_fees_data);
        }
        return $model;
    }

    public function findWhereInWithOrderBy($column, array $values, $type = 'desc', $attr = 'created_date', $conditions = [])
    {
        return $this->model::whereIn($column, $values)
            ->where($conditions)
            ->orderBy($attr, $type)
//            ->where('company_group_id', auth()->user()->company_group_id)
//            ->where('created_user', auth()->user()->user_id)
//            ->where('waybill_type_id', 1)
            ->get();
    }
}
