<?php

namespace App\Http\Controllers;


use App\Models\Payment;
use App\Models\Trigger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;

class RazorpayController extends Controller
{
    public function home()
    {

//        $output = [];
//        $returnVar = 0;
//        $result = exec(env('PYTHON').' '.env('PYTHONURL'),$output,$returnVar);
//
//        if ($returnVar === 0) {
//            return response()->json(['output' => $output]);
//        }
//
//        return response()->json(['error' => $result->errorOutput()], 500);
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
//                toastr()->error('DslrBooth not started yet.');
                return redirect()->back();
            }

            if ($jsonDecode['IsSuccessful'] == true) {
                return redirect()->route('success');
            } else {
//                toastr()->error($jsonDecode['ErrorMessage']);
                return redirect()->back();
            }
        } else {
//            toastr()->error('Payment Failed');
            return redirect()->back();
        }

    }

    public function success()
    {
//        toastr()->success('Payment Success');
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
//            toastr()->error('DslrBooth not started yet.');
            return redirect()->route('home');
        }

        if ($jsonDecode['IsSuccessful'] == true) {
//            toastr()->success('Process Completed.');
            return redirect()->route('home');
        } else {
//            toastr()->error($jsonDecode['ErrorMessage']);
            return redirect()->route('home');
        }
    }

    public function start()
    {
        $url = "https://api.razorpay.com/v1/customers";
        $key = "Basic " . base64_encode(env('RAZORPAY_KEY') . ":" . env('RAZORPAY_SECRET'));

        $header = array(
            "Content-Type: application/json",
            'Authorization: ' . $key
        );
        $postdata = '{
        "name":"asdssdfsfad",
            "email": "asdsdasad@ads.sad",
            "contact":"919131067303",

        }';
        $ch = curl_init();

        $timeout = 60;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // Values: GET, POST, PUT, DELETE, PATCH, UPDATE
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        //curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        //execute call and return response data.
        $result = curl_exec($ch);
        //close curl connection
        curl_close($ch);
        // decode the json response
        $customerRes = json_decode($result, true);

        if (isset($customerRes['error'])) {

            $errMessage = $customerRes['error']['description'];

            echo json_encode(['res' => 'error', 'message' => $errMessage]);
            exit;
        } else {
            $customerId = $customerRes['id'];
            $amount = env('AMOUNT');
            $qrNote = "QR code payment of 1";
            $pdesc = "Razorpay QR code Payment";
            $expiretime = '';
            $qrpostData = '{
                    "type": "upi_qr",
                    "name": "Hatk",
                    "usage": "single_use",
                    "fixed_amount": true,
                    "payment_amount": ' . $amount . ',
                    "description": "",
                    "customer_id": "' . $customerId . '",

                    "notes": {
                        "purpose": "Test UPI QR code notes"
                    }
                }';

            $url = "https://api.razorpay.com/v1/payments/qr_codes";

            $header = array(
                "Content-Type: application/json",
                'Authorization: ' . $key
            );

            $ch = curl_init();

            $timeout = 60;

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // Values: GET, POST, PUT, DELETE, PATCH, UPDATE
            curl_setopt($ch, CURLOPT_POSTFIELDS, $qrpostData);
            //curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            //execute call and return response data.
            $result2 = curl_exec($ch);
            //close curl connection
            curl_close($ch);
            // decode the json response
            $res = json_decode($result2, true);


            if (isset($res['id'])) {
                $qrID = $res['id'];
                $qrImage = $res['image_url'];
                $payment = new Payment();
                $payment->cust_id = $customerId;
                $payment->qr_id = $qrID;
                $payment->save();
                return view('qr', compact('customerId', 'qrID', 'qrImage'));
            }


//            if (isset($res['items']['0']['image_url'])) {
//                $qrID = $res['items']['0']['id'];
//                $qrImage = $res['items']['0']['image_url'];
////                echo json_encode(['res'=>'success','customer_id'=>$customerId,'qr_id'=>$qrID,'qrImage'=>$qrImage]); exit;
//                return view('qr', compact('customerId', 'qrID', 'qrImage'));
//            }

        }
    }

    public function payCheck(Request $request)
    {
        $url = "https://api.razorpay.com/v1/payments/qr_codes/$request->qr_id/payments?count=2";
        $key = "Basic " . base64_encode(env('RAZORPAY_KEY') . ":" . env('RAZORPAY_SECRET'));

        $header = array(
            'Authorization: ' . $key
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
        $result2 = curl_exec($ch);
        //close curl connection
        curl_close($ch);
        // decode the json response
        $res = json_decode($result2, true);

        if (isset($res['count']) && $res['count'] != '0') {
            $payment = Payment::where('qr_id', $request->qr_id)->first();
            $payment->raz_id = $res['items'][0]['id'];
            $payment->amount = $res['items'][0]['amount'];
            $payment->status = $res['items'][0]['status'];
            $payment->pay_method = $res['items'][0]['method'];
            $payment->payment_created_at = $res['items'][0]['created_at'];
            $payment->update();

            echo json_encode(['res' => 'success', 'payid' => $res['items'][0]['id'], 'ress' => $res]);
            exit;
        }
    }

    public function payCheck1(Request $request)
    {
        $url = "https://api.razorpay.com/v1/payments/qr_codes/qr_ONhMkb0xF7Hu7C/payments?count=2";
        $key = "Basic " . base64_encode(env('RAZORPAY_KEY') . ":" . env('RAZORPAY_SECRET'));

        $header = array(
            'Authorization: ' . $key
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
        $result2 = curl_exec($ch);
        //close curl connection
        curl_close($ch);
        // decode the json response
        $res = json_decode($result2, true);

        if (isset($res['count']) && $res['count'] != '0') {
            dd($res);

        }
        dd($res);
    }

    public function hitdslr()
    {
        $password = env('DSLRPASS');
        $url = "http://localhost:1500/api/start?mode=print&password=$password";

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
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // Values: GET, POST, PUT, DELETE, PATCH, UPDATE
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
//        if ($jsonDecode == NULL) {
//            toastr()->error('DslrBooth not started yet.');
//            return redirect()->back();
//        }


        if ($jsonDecode['IsSuccessful'] == true) {

            $output = [];
            $returnVar = 0;
            $result = exec(env('PYTHON') . ' ' . env('PYTHONURL'), $output, $returnVar);

            if ($returnVar === 0) {
                return response()->json(['output' => $output]);
            }

            return response()->json(['error' => $result->errorOutput()], 500);

//            $url = "http://localhost:3000/open-browser?event_type=minimize";
//
//            $header = array(
//                "Content-Type: application/json",
//                'Accept: application/json'
//            );
//            $ch = curl_init();
//
//            $timeout = 60;
//
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); // Values: GET, POST, PUT, DELETE, PATCH, UPDATE
//            curl_setopt($ch, CURLOPT_POSTFIELDS, false);
//            //curl_setopt($ch, CURLOPT_POST, true);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//            //execute call and return response data.
//            $result = curl_exec($ch);
//            //close curl connection
//            curl_close($ch);
//            // decode the json response
//            $jsonDecode = json_decode($result, true);

            //code for close browser
//            shell_exec('taskkill /f /im "firefox.exe"');
            return redirect()->route('home');
        } else {
//            toastr()->error($jsonDecode['ErrorMessage']);
            return redirect()->back();
        }
    }

    public function trigger(Request $r)
    {
        if ($r->event_type == 'session_end') {
            $url = "http://localhost:3000/open-browser?event_type=minimize";

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
        }

    }

    public function close()
    {
        shell_exec('taskkill /f /im "firefox.exe"');
//        $batchFilePath = storage_path('a.bat'); // Path to your .bat file
//
//        // Run the .bat file
//        $output = [];
//        $resultCode = 0;
//        exec("start /B cmd /C \"$batchFilePath\"", $output, $resultCode);
//
//        return response()->json([
//            'output' => $output,
//            'resultCode' => $resultCode
//        ]);
//        return view('close');
    }

    public function triggerB(Request $r)
    {
        if ($r->event_type == 'session_end') {
            $output = [];
            $returnVar = 0;
            $result = exec(env('PYTHON') . ' ' . env('PYTHONURL'), $output, $returnVar);

            if ($returnVar === 0) {
                return response()->json(['output' => $output]);
            }

            return response()->json(['error' => $result->errorOutput()], 500);
        }
    }
}
