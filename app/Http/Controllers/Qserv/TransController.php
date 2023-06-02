<?php

namespace App\Http\Controllers\Qserv;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FuelTransaction;
use App\Models\Company;
use App\Models\Branch;
use App\Models\CompanyMenuSerial;
use App\Models\StationInvoiceQR;
use App\Models\SystemCode;
use App\InvoiceQR\QRDataGenerator;
use App\InvoiceQR\SellerNameElement;
use App\InvoiceQR\TaxAmountElement;
use App\InvoiceQR\TaxNoElement;
use App\InvoiceQR\TotalAmountElement;
use App\InvoiceQR\InvoiceDateElement;
use Carbon\Carbon;

class TransController extends Controller
{
    //

    public function getTransById()
    {
        $trasn = FuelTransaction::where('r_transaction_Id','=',1)->first();
        return [
            "status" => 200,
            "print" => [
                "pumpside" => $trasn->nozzle_id,
                "grade" => "benzene91",
                "volume" => $trasn->volume,
                "amount" => $trasn->amount,
                "price"  => $trasn->price,
                "id"  => "2023012411120",
                "paymentMethod"  => $trasn->payment_method,
                "uuid" => $trasn->price,
                "qr" =>  '',
            ],
         
        ];
    }


    public function getInvoiceQR(Request $request)
    {
        $rules = [
            'stationId' => 'required|exists:branches,station_id',
            // 'transactionId' => 'required|numeric',
            'zoneId' => 'required|numeric',
            'fuelType' => 'required|exists:system_codes,system_code_filter',
            'nozzleId' => 'required|numeric',
            'pumpside' => 'required|numeric',
            'grade' => 'required|string',
            'volume' => 'required|numeric',
            'amount' => 'required|numeric',
            'price' => 'required|numeric',
            'id' => 'required|string',
            'paymentMethod' => 'required|numeric'
        ];
       
        $validator = Validator::make($request->all(), $rules);
         
        if($validator->fails())
        {
            return [
                "status" => 400,
                "msg" => implode(",", $validator->messages()->all()),
            ];
        }
        
        $inv = StationInvoiceQR::where('r_id','=',$request->id);
        if($inv->count())
        {
            $inv = $inv->first();
        }
        else{

            
            $branch = Branch::where('station_id', $request->stationId)->first();
            $company = $branch->company;
            $grade = SystemCode::where('company_id', $company->company_id)->where('system_code_filter', '=', $request->fuelType)->whereIn('system_code', ['70001' ,'70002', '70003'])->first();

            $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch->branch_id)->where('app_menu_id', 148);
            if (!$current_serial->count()) {
                return [
                    "status" => 500,
                    "msg" => 'missing data please contact the administrator'
                ];
            }
            $current_serial = $current_serial->first();
            $new_serial = 'INV-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);

            $vat_rate = 1.15;
            $total_vat = $request->amount - ($request->amount / $vat_rate);
            $qr = QRDataGenerator::fromArray([
                new SellerNameElement($company->companyGroup->company_group_ar),
                new TaxNoElement($company->company_tax_no),
                new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
                new TotalAmountElement($request->amount),
                new TaxAmountElement($total_vat)
            ])->toBase64();

            \DB::beginTransaction();
            $inv = new StationInvoiceQR;

            $inv->uuid = \DB::raw('NEWID()');
            $inv->company_group_id = $company->company_group_id;
            $inv->company_id = $company->company_id;
            $inv->branch_id = $branch->branch_id;
            $inv->station_id = $request->stationId;
            //$inv->transaction_id = $request->transactionId;
            $inv->nozzle_id = $request->nozzleId;
            $inv->zone_id = $request->zoneId;
            $inv->fuel_type = $request->fuelType;
            $inv->payment_method = $request->paymentMethod;
            $inv->pumpside = $request->pumpside;
            $inv->grade =  $grade->system_code_name_en;
            $inv->grade_ar = $grade->system_code_name_ar;
            $inv->volume = $request->volume;
            $inv->amount = $request->amount ;    
            $inv->price =  $request->price; 
            $inv->r_id = $request->id;
            $inv->vate_rate = abs(1 - $vat_rate);
            $inv->total_vat = $total_vat;
            $inv->inv_code = $new_serial;
            $inv->inv_date =  Carbon::now();   
            if($request->employeeId) {
                $inv->employee_id = $request->employeeId;
            }
           
            $inv->qr = $qr;
            $inv->created_by = 0;
            $inv->print_status = 'printed';

            $inv_save = $inv->save();

            if(!$inv_save)
            {
                return [
                    "status" => 500,
                    "msg" => 'pleas contact adminstrator'
                ];
            }

            $current_serial->update(['serial_last_no' => $new_serial]);
            \DB::commit();
            $inv = $inv->refresh();

        }
       
        return [

            "status" => 200,
            "print" => [
                // "transactionId" => $inv->transaction_id,
                "stationId" => $inv->station_id,
                "zoneId" => $inv->zone_id,
                "fuelType" => $inv->fuel_type,
                "nozzleId" => $inv->nozzle_id,
                "paymentMethod"  => $inv->payment_method,
                "pumpside" => $inv->pumpside,
                "grade" => $inv->grade,
                "gradeAr" => $inv->grade_ar,
                "volume" => $inv->volume,
                "amount" => $inv->amount,
                "price"  => $inv->price,
                "id"  => $inv->r_id,
                "totalVat" => $inv->total_vat,
                "invCode" => $inv->inv_code,
                "invDate" => $inv->inv_date->format('Y-m-d h:m:s a'),
                "uuid" => $inv->uuid,
                "qr" => $inv->qr
            ]
         
        ];
    }

}
