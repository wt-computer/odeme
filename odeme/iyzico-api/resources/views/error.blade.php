<!DOCTYPE html>
<html>
<head>
    <title>Ödeme Hatası</title>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ödeme Hatası</div>

                <div class="card-body">
                    @if(session('error_message'))
                        <div class="alert alert-danger">
                            {{ session('error_message') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<a href="/payment">Ödeme Sayfasına Dön</a>
</body>
</html>
