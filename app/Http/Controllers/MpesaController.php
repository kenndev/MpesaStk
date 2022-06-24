<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Validator;

class MpesaController extends Controller
{
    public function Mpesa()
    {
        return Inertia::render('Mpesa');
    }

    public function ProcessMpesa(Request $request)
    {
        $inputs = $request->all();
        $rule = array(
            'amount' => ['required', 'numeric'],
            'phonenumber' => ['required', 'numeric'],
        );

        Validator::make($inputs, $rule)->validate();

        $order = new Collection();
        $order->id = 18;
        $order->price = $request->input('amount');

        //return $this->paymentRequest($order, $request->input('phonenumber'));
        return redirect()->back()->with('errormessage', 'bloddy works');
    }

    public function getAccessKey()
    {
        $url = env('MPESA_ENV') == 0
            ? 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            : 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $curl = curl_init($url);
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_HTTPHEADER => ['Content-Type: application/json; charset=utf8'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_USERPWD => 'tr8nw2MHOqYFiqZnnX3HbO4r2nrLA8O7:6Ps46xEV2EJ5uoff'
            )
        );
        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        // return $response;
        return $response->access_token;
    }

    public function paymentRequest($order, $phonenumber)
    {
        $access_key = $this->getAccessKey();
        date_default_timezone_set('Africa/Nairobi');
        $timestamp  = date('YmdHis');
        $password = base64_encode("174379" . "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919" . $timestamp);
        $curl = curl_init('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_key,
            'Content-Type: application/json'
        ]);

        $curl_post_data = [
            "BusinessShortCode" => '174379',
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => '1',
            'PartyA' => '2547083741497',
            'PartyB' => '174379',
            'PhoneNumber' => '2547083741497',
            'CallBackURL' => 'http://localhost/payment',
            'AccountReference' => 'CompanyXLTD',
            'TransactionDesc' => 'Payment of X'
        ];

        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);
        curl_close($curl);
        $reponse = json_decode($curl_response);
        Log::info("Stk request");
        Log::info($curl_response);

        // if (isset($reponse->errorMessage)) {
        //     return redirect()->back()->with('errormessage', $reponse->errorMessage);
        // } else {
        //     if ($reponse->ResponseCode == 0) {
        //         return redirect()->route('mpesa')->withInput()->with('errormessage', 'Please check you phone and enter your Mpesa pin to continue the payment process');
        //     } else {
        //         return redirect()->route('mpesa')->withInput()->with('errormessage', 'Invalid phone number. Please ensure your number is correct and registered to M-Pesa');
        //     }
        // }
        return redirect()->back()->with('errormessage', 'bloddy works');
    }

    private function makeCallbackUrl($status, $order)
    {
        return url("/payments/verify/?status=$status&order_id=$order->id");
    }

    public function verify(Request $request)
    {
        Log::info("Stk Confirmation call back");
        Log::info($request->all());
    }
}
