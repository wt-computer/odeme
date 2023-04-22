<?php
/*
Laravel projesinde iyzico ödeme entegrasyonunu gerçekleştirmek için aşağıdaki adımları takip edebilirsiniz:

İlk olarak, iyzico PHP SDK'sını Laravel projenize ekleyin. Bu işlemi Composer ile yapabilirsiniz:
bash
Copy code
composer require iyzico/iyzipay-php
config klasöründe iyzico.php adında bir dosya oluşturun ve iyzico API anahtarlarınızı bu dosyada saklayın:
php
Copy code
// config/iyzico.php
return [
    'api_key' => env('IYZICO_API_KEY', ''),
    'secret_key' => env('IYZICO_SECRET_KEY', ''),
    'base_url' => env('IYZICO_BASE_URL', 'https://sandbox-api.iyzipay.com'), // Sandbox ortamı için
    // 'base_url' => env('IYZICO_BASE_URL', 'https://api.iyzipay.com'), // Canlı ortam için
];
.env dosyanızda ilgili anahtarları ve değerleri tanımlayın:
makefile
Copy code
IYZICO_API_KEY=API_KEY
IYZICO_SECRET_KEY=SECRET_KEY
IYZICO_BASE_URL=https://sandbox-api.iyzipay.com
Laravel projenizde bir controller oluşturun, örneğin app/Http/Controllers/PaymentController.php:
php
Copy code
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Iyzipay\Options;
use Iyzipay\Model\Locale;
use Iyzipay\Model\Currency;
use Iyzipay\Model\PaymentChannel;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Model\Payment;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Request\CreatePaymentRequest;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        // Ödeme işlemi kodları buraya gelecek
    }
}
PaymentController içindeki pay fonksiyonunu aşağıdaki gibi doldurun:
php
Copy code
public function pay(Request $request)
{
    // İyzico API bilgilerini al
    $apiKey = config('iyzico.api_key');
    $secretKey = config('iyzico.secret_key');
    $baseUrl = config('iyzico.base_url');

    // Ödeme işlemi için örnek kodu buraya yapıştırın ve gerekli değişiklikleri yapın.

    // Dinamik değerleri $request'ten alabilirsiniz, örneğin:
    // $cardHolderName = $request->input('card_holder_name');
}
Projedeki routes/web.php dosyasında PaymentController'ı kullanacak bir rota tanımlayın:
php
Copy code
use App\Http\Controllers\PaymentController;

Route::post('/pay', [PaymentController::class, 'pay']);
Ödeme sayfanızda, kullanıcının ödeme bilgilerini gireceği bir form oluşturun ve formun action özelliğini /pay olarak ayarlayın. Örnek olarak resources/views/payment.blade.php dosyasında aşağıdaki gibi bir form oluşturabilirsiniz:

html
Copy code
<!DOCTYPE html>
<html>
<head>
    <title>Ödeme Sayfası</title>
</head>
<body>
    <h1>Ödeme Sayfası</h1>
    <form action="/pay" method="POST">
        @csrf
        <label for="card_holder_name">Kart Sahibi Adı:</label>
        <input type="text" id="card_holder_name" name="card_holder_name" required><br>
        <label for="card_number">Kart Numarası:</label>
        <input type="text" id="card_number" name="card_number" required><br>
        <label for="expire_month">Son Kullanma Ayı:</label>
        <input type="text" id="expire_month" name="expire_month" required><br>
        <label for="expire_year">Son Kullanma Yılı:</label>
        <input type="text" id="expire_year" name="expire_year" required><br>
        <label for="cvc">CVC:</label>
        <input type="text" id="cvc" name="cvc" required><br>
        <input type="submit" value="Ödeme Yap">
    </form>
</body>
</html>
Bu formda, kullanıcının kart bilgilerini girebilmesi için gerekli alanlar mevcuttur. Bu örnek için basit bir form kullanılmıştır, gerçek projelerde daha güvenli ve kullanıcı dostu bir form tasarımı kullanmalısınız.

Son olarak, ödeme işleminin sonucunu göstermek için PaymentController içindeki pay fonksiyonunu şu şekilde düzenleyebilirsiniz:

php
Copy code
public function pay(Request $request)
{
    // İyzico API bilgilerini al
    $apiKey = config('iyzico.api_key');
    $secretKey = config('iyzico.secret_key');
    $baseUrl = config('iyzico.base_url');

    // Ödeme işlemi için örnek kodu buraya yapıştırın ve gerekli değişiklikleri yapın.

    // Dinamik değerleri $request'ten alabilirsiniz, örneğin:
    // $cardHolderName = $request->input('card_holder_name');

    // Ödeme işlemi sonucunu kontrol et
    if ($response->getStatus() == 'success') {
        return view('success', ['paymentId' => $response->getPaymentId()]);
    } else {
        return view('error', ['errorMessage' => $response->getErrorMessage()]);
    }
}
Burada, ödeme işlemi başarılı olduğunda success view'ını, hata durumunda ise error view'ını döndürüyoruz. Bu view'lar resources/views klasöründe oluşturulmalı ve içlerine ödeme sonucunu göstermek için gerekli HTML kodlarını eklemelisiniz.

Böylece, Laravel projesinde iyzico ödeme entegrasyonunu tamamlamış olacaksınız. Son olarak, başarılı ve hatalı ödeme işlemi sonuçlarını göstermek için resources/views klasöründe success.blade.php ve error.blade.php adında iki view dosyası oluşturun.

resources/views/success.blade.php:

html
Copy code
<!DOCTYPE html>
<html>
<head>
    <title>Ödeme Başarılı</title>
</head>
<body>
    <h1>Ödeme Başarılı</h1>
    <p>Ödeme ID: {{ $paymentId }}</p>
    <a href="/">Ana Sayfaya Dön</a>
</body>
</html>
resources/views/error.blade.php:

html
Copy code
<!DOCTYPE html>
<html>
<head>
    <title>Ödeme Hatası</title>
</head>
<body>
    <h1>Ödeme Hatası</h1>
    <p>Hata Mesajı: {{ $errorMessage }}</p>
    <a href="/payment">Ödeme Sayfasına Dön</a>
</body>
</html>
Artık Laravel projesinde iyzico ile ödeme entegrasyonunu tamamladınız. Kullanıcılar ödeme formunu doldurarak ödeme işlemini gerçekleştirebilir ve işlem sonucuna göre başarılı ya da hatalı işlem bilgisi ekranda görüntülenir.

Unutmayın, bu örnek uygulamada basit ve temel bir tasarım kullanılmıştır. Gerçek projelerde daha güvenli ve kullanıcı dostu bir form tasarımı kullanmalı, projenizin gereksinimlerine göre gerekli kontroller ve özelleştirmeler yapılmalıdır. Ayrıca, canlı ortama geçmeden önce API anahtarlarınızı ve baseUrl değerini güncellemeniz gerektiğini unutmayın.
