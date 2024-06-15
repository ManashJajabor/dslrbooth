<?php

namespace App\Http\Controllers;


use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;

class RazorpayController extends Controller
{
    public function home()
    {
        return view('welcome');
    }

    public function store(Request $request)
    {
        $input = $request->all();


        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));


        $payment = $api->payment->fetch($input['razorpay_payment_id']);


        if (count($input) && !empty($input['razorpay_payment_id'])) {

            try {

                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));


            } catch (\Exception $e) {

                return $e->getMessage();

                Session::put('error', $e->getMessage());

                return redirect()->back();

            }

        }

        if ($response->status == "captured") {


            $Payment = new Payment();
            $Payment->raz_id = $response->id;
            $Payment->amount = $response->amount;
            $Payment->status = $response->status;
            $Payment->pay_method = $response->method;
            $Payment->refused_status = $response->refund_status;
            $Payment->contact = $response->contact;
            $Payment->email = $response->email;
            $Payment->payment_created_at = $response->created_at;
//            $Payment->response = (j)$response;
            $Payment->save();

            $url = "http://localhost:1500/api/start?mode=print&password=fGZQKdg69FpT1opQ";

            $header = array(
                "Content-Type: application/json",
                'Accept: application/json'
            );
            $ch = curl_init();

            $timeout = 60;

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); // Values: GET, POST, PUT, DELETE, PATCH, UPDATE
            curl_setopt($ch, CURLOPT_POSTFIELDS, false);
            //curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            //execute call and return response data.
            $result = curl_exec($ch);
            //close curl connection
            curl_close($ch);
            // decode the json response
            $jsonDecode = json_decode($result, true);
//            dd($jsonDecode);
            if ($jsonDecode == NULL) {
                toastr()->error('DslrBooth not started yet.');
                return redirect()->back();
            }

            if ($jsonDecode['IsSuccessful'] == true) {
                return redirect()->route('success');
            } else {
                toastr()->error($jsonDecode['ErrorMessage']);
                return redirect()->back();
            }
        } else {
            toastr()->error('Payment Failed');
            return redirect()->back();
        }

    }

    public function success()
    {
        toastr()->success('Payment Success');
        return view('success');
    }

    public function print(Request $r)
    {
        $url = "http://localhost:1500/api/print?count=1&password=fGZQKdg69FpT1opQ";

        $header = array(
            "Content-Type: application/json",
            'Accept: application/json'
        );
        $ch = curl_init();

        $timeout = 60;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); // Values: GET, POST, PUT, DELETE, PATCH, UPDATE
        curl_setopt($ch, CURLOPT_POSTFIELDS, false);
        //curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        //execute call and return response data.
        $result = curl_exec($ch);
        //close curl connection
        curl_close($ch);
        // decode the json response
        $jsonDecode = json_decode($result, true);

        if ($jsonDecode == NULL) {
            toastr()->error('DslrBooth not started yet.');
            return redirect()->route('home');
        }

        if ($jsonDecode['IsSuccessful'] == true) {
            toastr()->success('Process Completed.');
            return redirect()->route('home');
        } else {
            toastr()->error($jsonDecode['ErrorMessage']);
            return redirect()->route('home');
        }
    }
}
