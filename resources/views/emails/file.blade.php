<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin đặt vé xem phim</title>
    <script src="https://cdn.jsdelivr.net/jsbarcode/3.3.7/JsBarcode.all.min.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <style>
        .barcode {
            width: 300px; /* Chiều rộng */
            height: 150px; /* Chiều cao */
        }
    </style>
</head>
<body>
    
    
    <div style="text-align:center">
        <h1 class="barcode">
            @php
                $idCode = $bookTicketDetails->id_code;
                $length = strlen($idCode);
            @endphp
            {!! DNS1D::getBarcodeHTML(substr($idCode, -7), "C128", 5, 250) !!}
        </h1>
    </div>
    
</body>



</html>