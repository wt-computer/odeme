<?php

namespace App\Http\Controllers;

use http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Iyzipay\Model\CheckoutFormInitialize;
use Iyzipay\Model\Locale;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Model\InstallmentInfo;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Options;
use Iyzipay\Model\Payment;
use Iyzipay\Model\PaymentChannel;
use Iyzipay\Model\Currency;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Iyzipay\Request\CreatePaymentRequest;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $apiKey = config('iyzico.api_key');
        $secretKey = config('iyzico.secret_key');
        $baseUrl = config('iyzico.base_url');

        $options = new Options();
        $options->setApiKey($apiKey);
        $options->setSecretKey($secretKey);
        $options->setBaseUrl($baseUrl);


        $paymentRequest = new CreatePaymentRequest();
        $paymentRequest->setLocale(Locale::TR);
        $paymentRequest->setConversationId("123456789");
        $paymentRequest->setPrice("1.0");
        $paymentRequest->setPaidPrice("1.2");
        $paymentRequest->setCurrency(Currency::TL);
        $paymentRequest->setInstallment(1);
        $paymentRequest->setBasketId("B67832");
        $paymentRequest->setPaymentChannel(PaymentChannel::WEB);
        $paymentRequest->setPaymentGroup(PaymentGroup::PRODUCT);


        $buyer = new Buyer();
        $buyer->setId("BY789");
        $buyer->setName("John");
        $buyer->setSurname("Doe");
        $buyer->setGsmNumber("+905350000000");
        $buyer->setEmail("email@example.com");
        $buyer->setIdentityNumber("74300864791");
        $buyer->setLastLoginDate("2023-04-14 15:12:21");
        $buyer->setRegistrationDate("2023-04-14 15:12:21");
        $buyer->setRegistrationAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $buyer->setCity("Istanbul");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("34732");
        $buyer->setIp("85.34.78.112");
        $paymentRequest->setBuyer($buyer);

        $billingAddress = new Address();
        $billingAddress->setContactName("Jane Doe");
        $billingAddress->setCity("Istanbul");
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $billingAddress->setZipCode("34742");
        $paymentRequest->setBillingAddress($billingAddress);

        $shippingAddress = new Address();
        $shippingAddress->setContactName("Jane Doe");
        $shippingAddress->setCity("Istanbul");
        $shippingAddress->setCountry("Turkey");
        $shippingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $shippingAddress->setZipCode("34742");
        $paymentRequest->setShippingAddress($shippingAddress);

        // Sepetteki ürünlerin bilgilerini al (Bu örnekte statik bir dizi kullanılmıştır, gerçek projenizde dinamik olarak elde etmelisiniz)
        $cartItems = [
            // ...
        ];

        // Sepetteki ürünleri iyzico ödeme sistemine uygun bir şekilde dönüştür
        $basketItems = [];

        foreach ($cartItems as $cartItem) {
            $basketItem = new BasketItem();
            $basketItem->setId($cartItem['id']);
            $basketItem->setName($cartItem['name']);
            $basketItem->setCategory1($cartItem['category1']);
            $basketItem->setCategory2($cartItem['category2']);
            $basketItem->setItemType(BasketItemType::PHYSICAL);
            $basketItem->setPrice($cartItem['price']);

            $basketItems[] = $basketItem;
        }

        $paymentRequest->setBasketItems($basketItems);

        // Ödeme kartı bilgileri (gerçek projenizde, bu bilgileri kullanıcıdan form aracılığıyla almalısınız)
        $paymentCard = new PaymentCard();
        $paymentCard->setCardHolderName("John Doe");
        $paymentCard->setCardNumber("5528790000000008");
        $paymentCard->setExpireMonth("12");
        $paymentCard->setExpireYear("2030");
        $paymentCard->setCvc("123");
        $paymentCard->setRegisterCard(0);
        $paymentRequest->setPaymentCard($paymentCard);

        // Ödeme işlemi gerçekleştir
        $payment = Payment::create($paymentRequest, $options);

        // Ödeme işlemi sonucunu kontrol et
        if ($payment->getStatus() == "success") {
            $request->session()->flash('payment_id', $payment->getPaymentId());

            // Kullanıcıyı başarılı ödeme sayfasına yönlendir
            return redirect('/success');
        } else {

            $errorMessage = $payment->getErrorMessage();
            $request->session()->flash('error_message', 'Ödeme işlemi başarısız. Hata: ' . $errorMessage);
            return redirect('/error');
        }
    }

    public function createPaymentForm(){
        $apiKey = config('iyzico.api_key');
        $secretKey = config('iyzico.secret_key');
        $baseUrl = config('iyzico.base_url');

        $options = new Options();
        $options->setApiKey($apiKey);
        $options->setSecretKey($secretKey);
        $options->setBaseUrl($baseUrl);
        $request = new CreateCheckoutFormInitializeRequest();
        // Gerekli bilgileri $request objesine ekleyin. Dökümantasyondaki örnekleri inceleyebilirsiniz: https://dev.iyzipay.com/tr/api/checkout-form

        $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);

        return view('payment', ['formContent' => $checkoutFormInitialize->getCheckoutFormContent(), 'checkoutFormUrl' => $checkoutFormInitialize->getCheckoutFormContent()]);
    }
}

