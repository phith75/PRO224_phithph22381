<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    //
    public function post_money(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'integer|required',
            'coin' => 'integer|required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $coin = $request->coin;

        // cap nhat coin nap vao
        $coin_total = User::find($request->id_user);

        if (!$coin) {
            return response()->json(['message' => 'giao dịch chưa hoàn thành do lỗi trong lúc nạp coin'], 404);
        }

        $coin += $coin_total->coin;
        $coin_total->update(['coin' => $coin]);

        // Redirect to a new route or reload the current page after processing the form
        return response()->json(['status' => 'success', 'message' => 'giao dịch thành công'], 200);
    }

    public function coin_payment(Request $request)
    {   
       
        $id_code = generateRandomString();
        $amount = (int)$request->amount;
        $data = [
            "id_code" => $id_code,
            "amount" => $amount
        ];

        $user = User::find($request->id);
        if (!$user) {
            return response()->json(['message' => 'Sai thông tin người dùng'], 404);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'amount' => 'integer',
            'password' => 'required|string', // Thêm quy tắc kiểm tra mật khẩu
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (Hash::check($request->input('password'), $user->password)) {
            if ($user->coin >= $amount) {
                $coin = $user->coin - $amount;
                $user->update(["coin" => $coin]);
                // Thêm data vào response nếu cần
                $response = [
                    'msg' => 'Thanh toán thành công',
                    'data' => $data
                ];
                return response()->json($response, 200);
            }

            return response()->json(['msg' => 'Số dư của bạn không đủ'], 200);
        } else {
            return response()->json(['msg' => 'Nhập sai mật khẩu, vui lòng thử lại!'], 201);
        }
    }
    public function vnpay_payment(Request $request)
    {
        $id_code = generateRandomString();

        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $startTime = date("YmdHis");
        $expire = date('YmdHis', strtotime('+50 minutes', strtotime($startTime)));
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:5173/payment/id_code=" . $id_code . '/'; // Đường dẫn return sau khi thanh toán


            if ($request->id_user != null) {
                $vnp_Returnurl = "http://localhost:5173/ResultNapTien/id_code=" . $id_code . '/'; // Đường dẫn return sau khi thanh toán
    
            }
            $vnp_TmnCode = "SMWBPLOI"; //Mã website tại VNPAY 
            $vnp_HashSecret = "YCXCIZUKOICUEMGAZGIFLYLLNULOSTTK"; //Chuỗi bí mật
            $vnp_TxnRef = $startTime; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
            $vnp_OrderInfo = 'Thanh toán hóa đơn';
            $vnp_OrderType = 'billpayment';
            $amount = (int)$request->amount;
            $vnp_Amount = $amount * 100;
            $vnp_Locale = 'vn';
            $vnp_BankCode = '';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            //Add Params of 2.0.1 Version
            $vnp_ExpireDate = $expire;
            //Billing
            // $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
            // $vnp_Bill_Email = $_POST['txt_billing_email'];
            // $fullName = trim($_POST['txt_billing_fullname']);
            // if (isset($fullName) && trim($fullName) != '') {
            //     $name = explode(' ', $fullName);
            //     $vnp_Bill_FirstName = array_shift($name);
            //     $vnp_Bill_LastName = array_pop($name);
            // }
            // $vnp_Bill_Address = $_POST['txt_inv_addr1'];
            // $vnp_Bill_City = $_POST['txt_bill_city'];
            // $vnp_Bill_Country = $_POST['txt_bill_country'];
            // $vnp_Bill_State = $_POST['txt_bill_state'];
            // // Invoice
            // $vnp_Inv_Phone = $_POST['txt_inv_mobile'];
            // $vnp_Inv_Email = $_POST['txt_inv_email'];
            // $vnp_Inv_Customer = $_POST['txt_inv_customer'];
            // $vnp_Inv_Address = $_POST['txt_inv_addr1'];
            // $vnp_Inv_Company = $_POST['txt_inv_company'];
            // $vnp_Inv_Taxcode = $_POST['txt_inv_taxcode'];
            // $vnp_Inv_Type = $_POST['cbo_inv_type'];
            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate" => $vnp_ExpireDate,
                // "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
                // "vnp_Bill_Email" => $vnp_Bill_Email,
                // "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
                // "vnp_Bill_LastName" => $vnp_Bill_LastName,
                // "vnp_Bill_Address" => $vnp_Bill_Address,
                // "vnp_Bill_City" => $vnp_Bill_City,
                // "vnp_Bill_Country" => $vnp_Bill_Country,
                // "vnp_Inv_Phone" => $vnp_Inv_Phone,
                // "vnp_Inv_Email" => $vnp_Inv_Email,
                // "vnp_Inv_Customer" => $vnp_Inv_Customer,
                // "vnp_Inv_Address" => $vnp_Inv_Address,
                // "vnp_Inv_Company" => $vnp_Inv_Company,
                // "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
                // "vnp_Inv_Type" => $vnp_Inv_Type
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                $inputData['vnp_Bill_State'] = $vnp_Bill_State;
            }

            //var_dump($inputData);
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            $returnData = array(
                'code' => '00', 'message' => 'success', 'data' => $vnp_Url
            );
            if (isset($_POST['redirect'])) {
                header('Location: ' . $vnp_Url);
                die();
            } else {
                return $returnData;
            }
            // vui lòng tham khảo thêm tại code demo
        }
    public function momo_payment(Request $request)
    {
        $id_code = generateRandomString();
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $redirectUrl = "http://localhost:5173/PayMentMoMo/id_code=" . $id_code . '/';;
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';


        $orderInfo = "Thanh toán qua momo";
        $amount = (int)$request->amount;

        $orderId = time() . "";
        $ipnUrl = "http://localhost:5173/";
        if ($request->id_user != null) {
            $redirectUrl = "http://localhost:5173/ResultNapTien/id_code=" . $id_code . '/'; // Đường dẫn return sau khi thanh toán

        }
        $extraData = "";
        $requestId = time() . "";
        $requestType = "captureWallet";
        // $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey  . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(

            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,

            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);  // decode json

        return ($jsonResult);
    }
}