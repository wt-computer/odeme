<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayTR Ödeme Formu</title>
</head>
<body>
<form id="paytr_form" method="post" action="https://www.paytr.com/odeme/guvenli">
    @foreach ($params as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
</form>

<script>
    document.getElementById('paytr_form').submit();
</script>
</body>
</html>
