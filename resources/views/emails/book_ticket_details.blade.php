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
            width: 180px;
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
            <div class="img"> <img src="./z4760362390886_2fb1e2afb6283d14f1bc424a20fe57f4.jpg" alt=""> </div>
            <p class="h2">Spider man: Far From Home</p>
            <div class="info">
                <div>
                    <p>Phòng chiếu: Sceen 1</p>
                </div>
                <div>
                    <p>Ghế: A1, A2</p>
                </div>
                <div>
                    <p>Ngày chiếu: 03/09/2023</p>
                </div>
                <div>
                    <p>Thời gian chiếu: 10:30</p>
                </div>
                <div>
                    <p>Đồ ăn: combo 2</p>
                </div>
                <div>
                    <p>Tổng tiền: 200.000 VNĐ</p>
                </div>
            </div>
        </main>
        <footer> <span>Địa chỉ: số 71 NCT, Hà Nội</span> </footer>
    </div>
</body>

</html>