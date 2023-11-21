<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            text-align: center;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }
        
        .container {
            box-sizing: border-box;
            width: 100%;
            border-radius: 6px;
            border: 1px solid black;
            margin: auto;
            background-color: black;
            color: white;
        }
        
        header {
            border-radius: 6px;
            width: 100%;
            height: 60px;
            background-color: #EA2B00;
            display: inline-block;
            text-align: center;
        }
        
        .h1 {
            text-align: center;
            font-size: 40px;
            font-weight: 700;
        }
        
        .img {
            margin-top: 20px;
            text-align: center;
        }
        
        img {
            height: 180px;
            border: 1px solid #EA2B00;
        }
        
        .h2 {
            text-align: center;
            font-size: 32px;
            margin: 20px 0px 20px 0;
            font-weight: 700;
        }
        
        .info p {
            margin: 10px 0;
            text-align: center;
        }
        
        footer {
            margin-top: 10px;
            width: 100%;
            height: 60px;
            text-align: center;
            padding-top: 20px;
            background-color: #EA2B00;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <p class="h1">VÉ XEM PHIM</p>
        </header>
        <main>
           @foreach ($bookTicketDetails as $bookTicketDetails)
               
           
            
            <div class="img"> <img src="{{$bookTicketDetails->image}}" alt=""> </div>
            <p class="h2">{{$bookTicketDetails->name}}</p>
            <div class="info">
                <div>
                    <p>MÃ VÉ: {{$bookTicketDetails->id_code}}</p>

                </div>
                <div>
                    <p>Phòng chiếu: {{$bookTicketDetails->name_cinema }}</p>
                </div>
                <div>
                    <p>Phòng chiếu: {{$bookTicketDetails->movie_room_name }}</p>
                </div>
                <div>
                    <p>Ghế:{{$bookTicketDetails->chair_name }}</p>
                </div>
                <div>
                    <p>Ngày chiếu:{{$bookTicketDetails->date }}</p>
                </div>
                <div>
                    <p>Thời gian chiếu:{{$bookTicketDetails->time_suatchieu }}</p>
                </div>

                <div>
                    <p>Đồ ăn: {{$bookTicketDetails->food_name }}</p>
                </div>
                <div>
                    <p>Thời gian thanh toán:{{$bookTicketDetails->time }}</p>
                </div>
                <div>
                    
                    <p>Tổng tiền: {{$bookTicketDetails->total_price }} VNĐ</p>
                </div>
            </div>
            @endforeach
        </main>
        <footer> <span>{{$bookTicketDetails->address }}</span> </footer>
    </div>

   


</body>

</html>