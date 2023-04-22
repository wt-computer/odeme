<!DOCTYPE html>
<html>
<head>
    <title>Ödeme Başarılı</title>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ödeme Başarılı</div>

                <div class="card-body">
                    @if(session('payment_id'))
                        <p>Ödeme ID: {{ session('payment_id') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<a href="/">Ana Sayfaya Dön</a>
</body>
</html>
