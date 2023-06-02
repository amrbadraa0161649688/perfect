<?php

namespace App\Http\Controllers\Api\Customer\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Payment\PaymentCheckoutRequest;
use App\Models\WaybillHd;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function paymentStatus(Request $request)
    {
        $paymethod = $request['paymethod'];
        if ($paymethod == "mada") {
            $entityId = env('PAYMENT_ENTITY_MADA', '8ac7a4c98779af9d018779df848f005e');
        } else {
            $entityId = env('PAYMENT_ENTITY_VISA', '8ac7a4c98779af9d018779dee7260059');
        }

        $url = env('PAYMENT_BASE_URL', 'https://test.oppwa.com/') . "v1/checkouts/" . $request["id"] . "/payment";
        $url .= "?entityId=" . $entityId;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer ' . env('PAYMENT_ACCESS_TOKEN', 'OGFjN2E0Yzk4Nzc5YWY5ZDAxODc3OWRlNmYzYTAwNDZ8M1BEOVJwRDltag==')
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        $responseData = json_decode($responseData, true);

        $trans_id = $request["id"];

        $transaction = WaybillHd::where('payment_details->transaction_id', $trans_id)->first();
        $payment_details = json_decode($transaction->payment_details);

        if (preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $responseData['result']['code']) || preg_match('/^(000\.400\.0[^3]|000\.400\.100)/', $responseData['result']['code'])) {
            $payment_details->status = 1;
            $transaction->update(['waybill_paid_amount', $responseData['amount']]);
        } else {
            $payment_details->status = 0;
        }
        $payment_details->msg = $responseData["result"]["description"];
        $transaction->update(['payment_details' => json_encode($payment_details)]);
        return responseSuccess([], __('messages.success'));
    }

    public function createCheckout(PaymentCheckoutRequest $request)
    {
        $user = auth()->user();
        $paymethod = $request['paymethod'];
        if ($paymethod == "mada") {
            $entityId = env('PAYMENT_ENTITY_MADA', '8ac7a4c98779af9d018779df848f005e');
        } else {
            $entityId = env('PAYMENT_ENTITY_VISA', '8ac7a4c98779af9d018779dee7260059');
        }
        $testMode = "&testMode=EXTERNAL";
        $amount = $request['amount'];

        $url = env('PAYMENT_BASE_URL', 'https://test.oppwa.com/') . "v1/checkouts";
        $data = "entityId=" . $entityId .
            "&amount=" . $amount .
            "&currency=SAR" .
            "&paymentType=DB" .
            $testMode .  // this should be remove  in production
            "&merchantTransactionId=" . md5(time()); //your unique id from the dataBase;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer ' . env('PAYMENT_ACCESS_TOKEN', 'OGFjN2E0Yzk4Nzc5YWY5ZDAxODc3OWRlNmYzYTAwNDZ8M1BEOVJwRDltag==')
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $responseData = json_decode($responseData, true);
//        dd($responseData);

        WaybillHd::where('waybill_id', $request->order_id)->update(['payment_details' => json_encode(['transaction_id' => $responseData['id']])]);

        return \response()->json(["url" => url('/api/application/payments/preview?id=' . $responseData['id'] . "&paymethod=" . $paymethod), "transaction_id" => $responseData['id']]);
    }

    public function checkoutPreview(Request $request)
    {
        $id = $request->id;
        $paymethod = $request->paymethod;

        return view('Payment.payment', \compact('id', 'paymethod'));
    }
}
