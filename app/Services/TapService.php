<?php

namespace App\Services;

use App\Mail\SendOrder;
use App\Models\BookingEffectivene;
use App\Models\BookingGift;
use App\Models\BookingTrip;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class TapService
{
    public $callback_url = '';
    public function pay($total)
    {
        try {
            $data["amount"] = $total;
            $data["currency"] = "SAR";
            $data["customer"]["first_name"] = auth()->user()->name;
            $data["customer"]["email"] = auth()->user()->email;
            $data["customer"]["phone"]["country_code"] = "966";
            $data["customer"]["phone"]["number"] = auth()->user()->phone;
            $data["merchant"]["id"] = config('tab.merchant_id');
            $data["source"]["id"] = "src_card";
            $data["redirect"]["url"] = $this->callback_url ;
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/charges",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer ".config('tab.secret_key'),
                "content-type: application/json"
            ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            return ['success' => true, 'data' => json_decode($response, true),'message' => ''];

        } catch (\Exception $e) {
            return ['success' => false, 'data' => null,'message' => $e->getMessage()];
        }
    }

    public function callback($tap_id, $type)
    {
        if ($type == "trip") {
            $order = BookingTrip::where('payment_id', $tap_id)->first();
        } elseif ($type == 'gift') {
            $order = BookingGift::where('payment_id', $tap_id)->first();
        } else {
            $order = BookingEffectivene::where('payment_id', $tap_id)->first();
        }
        if (!$order) {
            return ['success' => false,'data' => null,'message' => __('api.order_not_exist')];
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/charges/" . $tap_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer ".config('tab.secret_key'),
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $result = json_decode($response);

        $order->payment_status = $result->status;
        if ($result->status == 'CAPTURED') {
            $user = $order->user;
            Mail::to($user->email)->send(new SendOrder($user->email, $order->id));
        }
        $order->save();
        return ['success' => true,'data' => json_decode($response, true)];
    }


}
