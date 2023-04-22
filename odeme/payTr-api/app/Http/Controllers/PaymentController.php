<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function paytrPayment(Request $request)
    {
        $merchantId = env('PAYTR_MERCHANT_ID');
        $merchantKey = env('PAYTR_MERCHANT_KEY');
        $merchantSalt = env('PAYTR_MERCHANT_SALT');

        $orderId = 'ORDER-12345';
        $amount = 1000; // Kuruş cinsinden
        $currency = 'TL';
        $ip = $request->ip();
        $successUrl = route('payment.success');
        $failUrl = route('payment.fail');

        $user = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'address' => 'Example Street 123',
            'phone' => '+1234567890',
        ];

        $hashStr = $merchantId . $user['email'] . $orderId . $amount . $successUrl . $failUrl . $user['address'] . $user['phone'] . $currency . $ip;
        $paytrToken = base64_encode(hash_hmac('sha256', $hashStr . $merchantSalt, $merchantKey, true));

        $params = [
            'merchant_id' => $merchantId,
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_address' => $user['address'],
            'user_phone' => $user['phone'],
            'merchant_oid' => $orderId,
            'payment_amount' => $amount,
            'paytr_token' => $paytrToken,
            'debug_on' => 0,
            'test_mode' => 0,
            'no_installment' => 0,
            'max_installment' => 0,
            'currency' => $currency,
            'user_ip' => $ip,
            'merchant_ok_url' => $successUrl,
            'merchant_fail_url' => $failUrl,
            'timeout_limit' => 30,
            'lang' => 'tr',
        ];

        return view('payment.paytr_form', compact('params'));
    }

    public function paymentSuccess(Request $request)
    {
        // Ödeme başarılı olduğunda yapılacak işlemler
        print_r('success');
        //return view('payment.success');
    }

    public function paymentFail(Request $request)
    {
        // Ödeme başarısız olduğunda yapılacak işlemler
        return view('payment.fail');
    }
}
