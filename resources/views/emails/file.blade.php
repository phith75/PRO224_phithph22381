<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin đặt vé xem phim</title>
    <script src="https://cdn.jsdelivr.net/jsbarcode/3.3.7/JsBarcode.all.min.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

</head>


<body>
    @foreach ($bookTicketDetails as $bookTicketDetails)
    <p  
    style="Margin:0 0 0 0;Padding:0px 0 7px 0;text-align:center;font:24px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;text-transform:uppercase;color:#ec1c23">
    <b>
        @php
        $idCode = $bookTicketDetails->id_code;
        $length = strlen($idCode);
        @endphp
         <svg id="barcode"></svg>
         <script>
             JsBarcode("#barcode", "{{substr($idCode, -7) }}");
         </script>
    </b>
</p>
    @endforeach
</body>



</html>