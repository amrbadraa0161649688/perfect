<?php

namespace App\Http\Resources\CarRent;

use App\Models\CarPriceListDt;
use App\Models\CarPriceListHd;
use App\Models\Customer;
use App\Models\PriceListDt;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CarRentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request, $customer = null)
    {
//        $customer = Customer::where('customer_id', request()->customer_id)
//            ->with('activePriceList')->first();
//        $price_list_hds = CarPriceListHd::where('rent_list_status', '=', 1)
//            ->where('rent_list_start_date', '<', Carbon::now())
//            ->where('rent_list_end_date', '>', Carbon::now())->get();

//        if ($customer->activePriceList) {
//            $price_list_dt = $customer->activePriceList->priceListDetails
//                ->where('car_model_id', $this->car_rent_model_id)->first();
//
//        } else {
//            $sys_code = SystemCode::where('system_code', 538)
//                ->where('company_group_id', $customer->company_group_id)->first()->system_code_id;
//
//            $price_list_dt = CarPriceListDt::where('car_model_id', $this->car_rent_model_id)
//                ->whereHas('priceListHd', function ($query) use ($sys_code) {
//                    $query->where('rent_list_status', '=', 1)
//                        ->where('rent_list_start_date', '<', Carbon::now())
//                        ->where('rent_list_end_date', '>', Carbon::now())
//                        ->where('customer_type_id', '=', $sys_code);
//                })->first();
//
//        }

//        return $price_list_dt;

        $price_list_dt = isset($this->model->priceListDts->priceListHd)?$this->model->priceListDts->priceListHd->latest()->first():null;

        return [
            'car_id' => $this->car_id,
            'plate_number' => $this->full_car_plate,
            'brand_name_ar' => $this->brand ? $this->brand->brand_name_ar : '',
            'brand_name_en' => $this->brand ? $this->brand->brand_name_en : '',
            'car_rent_model_id' => $this->car_rent_model_id,
            'model_name_ar' => $this->brand->branddt ? $this->brandDetails->brand_dt_name_ar : '',
            'model_name_en' => $this->brand->branddt ? $this->brandDetails->brand_dt_name_en : '',
            'category_name_ar' => $this->category ? $this->category->system_code_name_ar : '',
            'category_name_en' => $this->category ? $this->category->system_code_name_en : '',
            'color' => $this->car_color,
            'last_odometer' => $this->last_odometer,
            'tracker_status_ar' => $this->truckerStatus ? $this->truckerStatus->system_code_name_ar : '',
            'tracker_status_en' => $this->truckerStatus ? $this->truckerStatus->system_code_name_ar : '',
            'car_model_year' => $this->car_model_year,
            'car_passengers' => $this->car_passengers,
            'car_operation_card_no' => $this->car_operation_card_no,
            'car_registration_type_ar' => $this->registrationType ? $this->registrationType->system_code_name_ar : '',
            'car_registration_type_en' => $this->registrationType ? $this->registrationType->system_code_name_en : '',
            'oil_change_km' => $this->oil_change_km,
            'insurance_document_no' => $this->insurance_document_no,
            'insurance_type' => $this->insurance_type,
            'insurance_type_ar' => $this->insuranceType ? $this->insuranceType->system_code_name_ar : '',
            'insurance_type_en' => $this->insuranceType ? $this->insuranceType->system_code_name_en : '',
            'insurance_date_end' => $this->insurance_type,
            'fuel_type_id' => $this->fuel_type_id,
            'fuel_type_name_ar' => $this->fuelType ? $this->fuelType->system_code_name_ar : '',
            'fuel_type_name_en' => $this->fuelType ? $this->fuelType->system_code_name_en : '',
            'odometer_start' => $this->odometer_start,

            'oil_type_ar' => $this->oilType ? $this->oilType->system_code_name_ar : '',
            'oil_type_en' => $this->oilType ? $this->oilType->system_code_name_en : '',
            'oil_type_id' => $this->oilType ? $this->oilType->system_code_id : '',

            'last_oil_change_date' => $this->last_oil_change_date,
//            'car_Safety_Triangle' => $this->car_Safety_Triangle,
            'car_Fire_extinguisher' => $this->car_Fire_extinguisher,

            'car_Radio_Stereo_status_ar' => $this->radioStatus ? $this->radioStatus->system_code_name_ar : '',
            'car_Radio_Stereo_status_en' => $this->radioStatus ? $this->radioStatus->system_code_name_en : '',
            'car_Radio_Stereo_status_id' => $this->radioStatus ? $this->radioStatus->system_code_id : '',

            'car_Safety_Triangle_ar' => $this->safetyTriangle ? $this->safetyTriangle->system_code_name_ar : '',
            'car_Safety_Triangle_en' => $this->safetyTriangle ? $this->safetyTriangle->system_code_name_en : '',
            'car_Safety_Triangle_id' => $this->safetyTriangle ? $this->safetyTriangle->system_code_id : '',

            'car_Screen_status_ar' => $this->screenStatus ? $this->screenStatus->system_code_name_ar : '',
            'car_Screen_status_en' => $this->screenStatus ? $this->screenStatus->system_code_name_en : '',
            'car_Screen_status_id' => $this->screenStatus ? $this->screenStatus->system_code_id : '',

            'car_Speedometer_status_ar' => $this->speedometerStatus ? $this->speedometerStatus->system_code_name_ar : '',
            'car_Speedometer_status_en' => $this->speedometerStatus ? $this->speedometerStatus->system_code_name_en : '',
            'car_Speedometer_status_id' => $this->speedometerStatus ? $this->speedometerStatus->system_code_id : '',

            'car_Seats_status_ar' => $this->seatsStatus ? $this->seatsStatus->system_code_name_ar : '',
            'car_Seats_status_en' => $this->seatsStatus ? $this->seatsStatus->system_code_name_en : '',
            'car_Seats_status' => $this->car_Seats_status,

            'car_Spare_Tire_tools_ar' => $this->spareTireTools ? $this->spareTireTools->system_code_name_ar : '',
            'car_Spare_Tire_tools_en' => $this->spareTireTools ? $this->spareTireTools->system_code_name_en : '',
            'car_Spare_Tire_tools_id' => $this->spareTireTools ? $this->spareTireTools->system_code_id : '',

            'car_Tires_status_ar' => $this->TiresStatus ? $this->TiresStatus->system_code_name_ar : '',
            'car_Tires_status_en' => $this->TiresStatus ? $this->TiresStatus->system_code_name_en : '',
            'car_Tires_status_id' => $this->TiresStatus ? $this->TiresStatus->system_code_id : '',

            'car_Spare_Tire_status_ar' => $this->spareTireStatus ? $this->spareTireStatus->system_code_name_ar : '',
            'car_Spare_Tire_status_en' => $this->spareTireStatus ? $this->spareTireStatus->system_code_name_en : '',
            'car_Spare_Tire_status_id' => $this->spareTireStatus ? $this->spareTireStatus->system_code_id : '',

            'car_keys_status_ar' => $this->keysStatus ? $this->keysStatus->system_code_name_ar : '',
            'car_keys_status_en' => $this->keysStatus ? $this->keysStatus->system_code_name_en : '',
            'car_keys_status_id' => $this->keysStatus ? $this->keysStatus->system_code_id : '',

            'car_First_Aid_Kit_ar' => $this->firstAidKit ? $this->firstAidKit->system_code_name_ar : '',
            'car_First_Aid_Kit_en' => $this->firstAidKit ? $this->firstAidKit->system_code_name_en : '',
            'car_First_Aid_Kit_id' => $this->firstAidKit ? $this->firstAidKit->system_code_id : '',

            'car_ac_status_ar' => $this->carAcStatus ? $this->carAcStatus->system_code_name_ar : '',
            'car_ac_status_en' => $this->carAcStatus ? $this->carAcStatus->system_code_name_en : '',
            'car_ac_status_id' => $this->carAcStatus ? $this->carAcStatus->system_code_id : '',

            'endurance_amount' => $this->insurance_value,
            'available_fuel' => $this->availableFuel,
            'allowed_km_per_hour' => $this->allowedKmPerHour,
//            'full_fuel_cost' => $this->fullFuelCost,
            'oilChangeKmDistance' => $this->oil_change_km,
            'call_date' => $this->call_date,

            'daily_price' => $price_list_dt ? $price_list_dt->rent_price : 0,
            'monthly_price' => $price_list_dt ? $price_list_dt->rent_price * 30 : 0,
            'extra_kilometer' => $price_list_dt ? $price_list_dt->extra_kilometer : 0,
            'extra_kilometer_price' => $price_list_dt ? $price_list_dt->extra_kilometer_price : 0,
            'hours_to_day' => $price_list_dt ? $price_list_dt->hours_to_day : 0,
            'extra_hour' => $price_list_dt ? $price_list_dt->extra_hour : 0,
            'extra_hour_price' => $price_list_dt ? $price_list_dt->extra_hour_price : 0,
            'extra_driver' => $price_list_dt ? $price_list_dt->extra_driver : 0,
            'rent_type' => $price_list_dt ? $price_list_dt->rentType : 0,
            'rent_type_name_ar' => $price_list_dt ? $price_list_dt->rentType->system_code_name_ar : 0,
            'rent_type_name_en' => $price_list_dt ? $price_list_dt->rentType->system_code_name_en : 0,
            'discount_value' => $price_list_dt ? $price_list_dt->discount_value : 0,
            'car_operation_card_date' => $this->car_operation_card_date,
//            'extra_kilometer' => $this->model->CurrentPrice ? $this->model->CurrentPrice->extra_kilometer : 0,
//            'extra_kilometer_price' => $this->model->CurrentPrice ? $this->model->CurrentPrice->extra_kilometer_price : 0,
//            'hours_to_day' => $this->model->CurrentPrice ? $this->model->CurrentPrice->hours_to_day : 0,
//            'extra_hour' => $this->model->CurrentPrice ? $this->model->CurrentPrice->extra_hour : 0,
//            'extra_hour_price' => $this->model->CurrentPrice ? $this->model->CurrentPrice->extra_hour_price : 0,
//            'extra_driver' => $this->model->CurrentPrice ? $this->model->CurrentPrice->extra_driver : 0,

        ];
    }
}
