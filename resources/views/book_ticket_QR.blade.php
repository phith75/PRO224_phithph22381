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
    <div class="">
        <div class="aHl"></div>
        <div id=":18g" tabindex="-1"></div>
        <div id=":186" class="ii gt"
            jslog="20277; u014N:xr6bB; 1:WyIjdGhyZWFkLWY6MTc1MzQxODE4Mzc1NjY3Mjg2MiJd; 4:WyIjbXNnLWY6MTc1MzQxODE4Mzc1NjY3Mjg2MiJd">
            <div id=":185" class="a3s aiL msg-3817474905348111517">
                <div class="adM">
                </div><u></u>
                <div
                    style="min-width:260px;min-height:100%;padding:0;padding-top:20px;padding-bottom:20px;padding-left:3px;padding-right:3px;Margin:0 auto;background-color:#efefef;font-weight:regular">

                    <div
                        style="background:#fff;Margin:0 auto;max-width:800px;min-width:260px;font-size:16px; box-shadow: 0 10px 15px -3px #06b6d480 ,0 10px 6px -4px #06b6d480;">
                        <div
                            style="width:800px;display:inline-block;font-size:18px;text-align:center;color:#333333;background:#ffffff;vertical-align:top;width:100%;min-width:160px;max-width:100%;width:-webkit-calc(230400px - 48000%);min-width:-webkit-calc(100%);width:calc(230400px - 48000%);min-width:calc(100%)">

                            <div style="Margin:0 0 0 0px">

                                <p>
                                    Hỗ trợ khách hàng: <a href="tel:*6789" style="text-decoration:none"
                                        target="_blank"><span style="color:#00b6ee"><b>*6789</b></span></a>
                                </p>

                            </div>

                        </div>
                        <div
                            style="width:800px;display:inline-block;font-size:15px;text-align:left;color:#333333;background:#081b3a;vertical-align:top;width:100%;min-width:160px;max-width:100%;width:-webkit-calc(230400px - 48000%);min-width:-webkit-calc(100%);width:calc(230400px - 48000%);min-width:calc(100%)">

                            <div style="Margin:0 0 0 0px">


                                <p
                                    style="Padding:0 0 0 0;Margin:10px 10px 20px 10px;text-align:center;font:14px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;color:#fff;line-height:24px">
                                    Quý khách đã mua vé xem phim thành công qua
                                    STC Cinema, vui
                                    lòng kiểm tra lại thông tin đặt vé dưới đây:
                                </p>

                            </div>

                        </div>
                        <div style="text-align:center;font-size:0">

                            <img src="{{$bookTicketDetails->image}}" width="800" height="400"
                                style="width:100%;max-width:800px;border:none;Margin:0 auto;Margin-bottom:0px;display:block;height:462px"
                                border="0" alt="VNPAY" title="vnpay" class="CToWUd a6T" data-bit="iit" tabindex="0">
                            <div class="a6S" dir="ltr" style="opacity: 0.01; left: 1004px; top: 590px;">
                                <div id=":190" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" role="button" tabindex="0"
                                    aria-label="Tải xuống tệp đính kèm "
                                    jslog="91252; u014N:cOuCgd,Kr2w4b,xr6bB; 4:WyIjbXNnLWY6MTc1MzQxODE4Mzc1NjY3Mjg2MiJd"
                                    data-tooltip-class="a1V" jsaction="JIbuQc:.CLIENT" data-tooltip="Tải xuống">
                                    <div class="akn">
                                        <div class="aSK J-J5-Ji aYr"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div
                            style="width:800px;display:inline-block;font-size:16px;text-align:left;color:#333333;background:#ffffff;vertical-align:top;width:100%;min-width:160px;max-width:100%;width:-webkit-calc(230400px - 48000%);min-width:-webkit-calc(100%);width:calc(230400px - 48000%);min-width:calc(100%)">

                            <div style="Margin:0 0 0 0px">

                                <p
                                    style="Padding:22px 10px 8px 5%;Margin:0;text-align:center;font:21px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;color:#000;line-height:24px">
                                    {{$bookTicketDetails->name}}
                                </p>

                            </div>

                        </div>
                        <div
                            style="width:800px;display:inline-block;font-size:16px;text-align:left;color:#333333;background:#ffffff;vertical-align:top;width:100%;min-width:160px;max-width:100%;width:-webkit-calc(230400px - 48000%);min-width:-webkit-calc(100%);width:calc(230400px - 48000%);min-width:calc(100%)">

                            <div style="Margin:0 0 0 0px">

                                <p
                                    style="Padding:0px 10px 8px 5%;Margin:0;text-align:center;font:14px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;color:#00b6ee;line-height:20px">
                                    <b>{{$bookTicketDetails->name_cinema }}</b>
                                </p>

                            </div>

                        </div>
                        <div
                            style="width:800px;display:inline-block;font-size:16px;text-align:left;color:#333333;background:#ffffff;vertical-align:top;width:100%;min-width:160px;max-width:100%;width:-webkit-calc(230400px - 48000%);min-width:-webkit-calc(100%);width:calc(230400px - 48000%);min-width:calc(100%)">

                            <div style="Margin:0 0 0 0px">

                                <p
                                    style="Padding:0px 10px 12px 5%;Margin:0;text-align:center;font:14px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;color:#676767;line-height:14px">
                                    {{$bookTicketDetails->address }}
                                </p>

                            </div>

                        </div>
                        <div
                            style="width:800px;display:inline-block;font-size:15px;text-align:left;background:#ffffff;vertical-align:top;width:100%;min-width:160px;max-width:100%;width:-webkit-calc(230400px - 48000%);min-width:-webkit-calc(100%);width:calc(230400px - 48000%);min-width:calc(100%)">

                            <div style="Margin:0 0 0 0px">

                                <div
                                    style="Padding:15px 5% 0 5%;Margin:0;text-align:left;font:15px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;color:#333333;line-height:28px;display:block">
                                    <div style="border:2px dashed #bcbcbc;Padding:20px 0px 20px 0px">

                                        <div
                                            style="display:inline-block;font-size:0;text-align:center;vertical-align:middle;width:42%;min-width:120px;max-width:100%;width:-webkit-calc(230400px - 48000%);min-width:-webkit-calc(32%);width:calc(230400px - 48000%);min-width:calc(42%)">
                                            <p
                                                style="Margin:10px 0 0 0;Padding:0px 0 0 0;text-align:center;font:12px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;text-transform:uppercase;line-height:12px">
                                                <b>Mã vé (Reservation code)</b>
                                            </p>
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
                                            <p
                                                style="Margin:0 0 0 0;Padding:0px 0 0 0;text-align:center;font:10px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;text-transform:uppercase;line-height:10px;display:none">
                                                <b>(Vui lòng mang mã vé để đổi bỏng nước)</b>
                                            </p>
                                           
                                            <p
                                                style="Margin:0 0 0 0;Padding:0px 0 0 0;text-align:center;font:12px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;text-transform:uppercase;line-height:12px">
                                                <b>Suất chiếu (Session)</b>
                                            </p>
                                            <p
                                                style="Margin:0 0 0 0;Padding:0px 0 0 0;text-align:center;font:18px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;text-transform:uppercase;color:#000;letter-spacing:2px;line-height:24px">
                                                <strong>
                                                    <?php

                                                    $formattedDate = date("d/m/Y", strtotime($bookTicketDetails->date));
                                                    echo $formattedDate;
                                                    ?>
                                                </strong>
                                            </p>
                                            <p
                                                style="Margin:0 0 10px 0;Padding:0px 0 0 0;text-align:center;font:18px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;text-transform:uppercase;color:#000;line-height:20px">
                                                {{$bookTicketDetails->time_suatchieu }}
                                            </p>
                                        </div>

                                        <div
                                            style="display:inline-block;font-size:0;text-align:center;vertical-align:middle;width:58%;min-width:120px;max-width:100%;width:-webkit-calc(230400px - 48000%);min-width:-webkit-calc(32%);width:calc(230400px - 48000%);min-width:calc(57%);border-left:0px dashed #929292">
                                            <p
                                                style="Margin:0px 4% 14px 4%;Padding:0 0 0 0;text-align:left;font:14px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;line-height:18px">
                                                Phòng chiếu (Hall):<br>
                                                <b>{{$bookTicketDetails->movie_room_name }}</b>
                                            </p>
                                            <table
                                            style="width:90%;margin-left:4%;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:19px">
                                            <tbody>
                                                <tr style="">
                                                    <td align="left" colspan="2" style="padding-bottom:10px">
                                                        <strong> Ghế (Seat):</strong>
                                                        <br>
                                                        <b>{{$bookTicketDetails->chair_name }}</b>
                                                    </td>
                                                    <td style="padding-bottom:10px">:</td>
                                                    <td align="right" style="padding-bottom:10px">
                                                    
                                                        <strong>{{ number_format(intval($bookTicketDetails->chair_price), 0, ',', '.') }}
                                                            đ</strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                            <p
                                                style="Margin:0px 4% 14px 4%;Padding:0 0 0 0;text-align:left;font:14px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;line-height:18px;display:none">
                                                Loại combo:
                                            </p>
                                            <p
                                                style="Margin:0px 4% 14px 4%;Padding:0 0 0 0;text-align:left;font:14px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;line-height:18px">
                                                Thời gian thanh toán (Payment Time):<br>
                                                <b> {{$bookTicketDetails->time}}</b>

                                            </p>
                                            <table
                                                style="width:90%;margin-left:4%;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:19px">
                                                <tbody>
                                                    <tr style="display:none">
                                                        <td align="left" colspan="2" style="padding-bottom:10px">
                                                            <strong>Tiền vé</strong>
                                                        </td>
                                                        <td style="padding-bottom:10px">:</td>
                                                        <td align="right" style="padding-bottom:10px">
                                                            <strong>{{ number_format($bookTicketDetails->total_price, 0, ',', '.') }}
                                                                đ</strong>
                                                        </td>
                                                    </tr>
                                                    <tr style="">
                                                        <td align="left" colspan="2" style="padding-bottom:10px">
                                                            <strong>Đồ ăn</strong>
                                                        </td>
                                                        <td style="padding-bottom:10px">:</td>
                                                        <td align="right" style="padding-bottom:10px">
                                                            <strong>
                                                                @foreach ($food_ticket_detail as $key => $food_detail)
                                                                    {{$food_detail->quantity}} *  {{$food_detail->name}}
                                                                    @if ($loop->last)
                                                                        <hr>
                                                                    @else
                                                                        <br>+
                                                                        <br>
                                                                    @endif
                                                                @endforeach
                                                            </strong>
                                                        </td>
                                                        
                                                    </tr>
                                                    
                                                    <tr style="">
                                                        <td align="left" colspan="2" style="padding-bottom:10px">
                                                            <strong>Tổng tiền đồ ăn</strong>
                                                        </td>
                                                        <td style="padding-bottom:10px">:</td>
                                                        <td align="right" style="padding-bottom:10px">
                                                            <strong>
                                                                @php
                                                                        $total_price = 0;   
                                                                @endphp
                                                                @foreach ($food_ticket_detail as $key => $food_detail)
                                                                @php
                                                                $total_price +=  ( intval($food_detail->price) *  intval($food_detail->quantity));
                                                                @endphp
                                                                                 
                                                                @endforeach
                                                                {{number_format($total_price, 0, ',', '.') }}
                                                            đ</strong>
                                                        </td>
                                                        
                                                    </tr>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" colspan="2" style="padding-bottom:10px">
                                                            <strong>Tổng tiền</strong>
                                                        </td>
                                                        <td style="padding-bottom:10px">:</td>
                                                        <td align="right" style="padding-bottom:10px">
                                                            <strong>{{ number_format($total_price +$bookTicketDetails->chair_price, 0, ',', '.') }}
                                                                đ</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" colspan="2" style="padding-bottom:10px">
                                                            <strong>Giảm giá từ voucher và điểm: </strong>
                                                        </td>
                                                        <td style="padding-bottom:10px">:</td>
                                                        <td align="right" style="padding-bottom:10px">
                                                            <strong>{{ number_format($total_price +$bookTicketDetails->chair_price - $bookTicketDetails->total_price, 0, ',', '.') }}
                                                                đ</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" colspan="2"><strong>Số tiền thanh toán</strong>
                                                        </td>
                                                        <td>:</td>
                                                        <td align="right" style="color:#ec1c23">
                                                            <strong>{{ number_format($bookTicketDetails->total_price, 0, ',', '.') }}
                                                                đ</strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>
                        <div
                            style="width:800px;display:inline-block;font-size:16px;text-align:left;color:#333333;background:#ffffff;vertical-align:top;width:100%;min-width:160px;max-width:100%;width:-webkit-calc(230400px - 48000%);min-width:-webkit-calc(100%);width:calc(230400px - 48000%);min-width:calc(100%)">

                            <div style="Margin:0 0 0 0px">

                                <p
                                    style="Padding:30px 10px 15px 5%;Margin:0;text-align:left;font:14px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;color:#000;line-height:20px">
                                    <b>Lưu ý/Note:</b>
                                </p>

                            </div>

                        </div>
                        <div
                            style="width:800px;display:inline-block;font-size:16px;text-align:left;color:#333333;background:#ffffff;vertical-align:top;width:100%;min-width:160px;max-width:100%;width:-webkit-calc(230400px - 48000%);min-width:-webkit-calc(100%);width:calc(230400px - 48000%);min-width:calc(100%)">

                            <div style="Margin:0 0 0 0px">

                                <p
                                    style="Padding:0px 10px 10px 5%;Margin:0;text-align:left;font:14px 'Arial','Helvetica Neue',Helvetica,'Myriad Pro',sans-serif;color:#000;line-height:20px">
                                    Vé đã mua có thể hoàn trước 2 tiếng khi suất chiếu diễn ra và chỉ có thể hoàn tối đa 2 lần 1 tháng <a
                                        href="https://qldt.vnpay.vn/vexemphim/dieu-khoan-dieu-le.html" target="_blank"
                                        data-saferedirecturl="https://www.google.com/url?q=https://qldt.vnpay.vn/vexemphim/dieu-khoan-dieu-le.html&amp;source=gmail&amp;ust=1700937506805000&amp;usg=AOvVaw2yBtkDxqG3BH4ODzvdjh-7">Điều
                                        khoản mua và sử dung vé xem phim</a> để biết thêm chi tiết. Cảm ơn bạn đã lựa
                                    chọn rạp chiếu phim STC của chúng tôi. Chúc bạn xem phim vui vẻ!
                                    <br><br>
                                    The successful movie ticket can be canceled, exchanged or can be refunded 2 hours in advance when the projection occurs and can only be refunded up to 2 times a month<a href="https://qldt.vnpay.vn/vexemphim/dieu-khoan-dieu-le.html"
                                        target="_blank"
                                        data-saferedirecturl="https://www.google.com/url?q=https://qldt.vnpay.vn/vexemphim/dieu-khoan-dieu-le.html&amp;source=gmail&amp;ust=1700937506805000&amp;usg=AOvVaw2yBtkDxqG3BH4ODzvdjh-7">Condition
                                        to purchase and use movie tickets</a> for more information. Thank you for
                                    choosing STC cinemas of us and Enjoy the movie!
                                    <br><span style="color:#fff">28/12/2022 08:11:14</span>
                                </p>
                                <div class="yj6qo"></div>
                                <div class="adL">

                                </div>
                            </div>
                            <div class="adL">

                            </div>
                        </div>
                        <div class="adL">

                        </div>
                    </div>
                    <div class="adL">

                    </div>
                </div>
                <div>

                    <p>Tổng tiền: {{$bookTicketDetails->total_price }} VNĐ</p>
                </div>
            </div>
        </div>
        <div class="hi"></div>
        <div class="WhmR8e" data-hash="0"></div>
    </div>
    @endforeach
   
</body>



</html>