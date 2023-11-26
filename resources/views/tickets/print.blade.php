<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Details</title>
    <style>
        /* Thêm các CSS phù hợp với giao diện in vé của bạn */
    </style>
</head>
<body>
    <?php
    ?>
    @foreach($array_chair as $key => $chair)
    <h1>Ticket Details</h1>
    <p><strong>Time:</strong> {{ $book_ticket_detail->time }}</p>
    <p><strong>Film Name:</strong> {{ $book_ticket_detail->name }}</p>
    <p><strong>Ticket Code:</strong> {{ $book_ticket_detail->id_code }}</p>
    <p><strong>Movie Room Name:</strong> {{ $book_ticket_detail->movie_room_name }}</p>
    <p><strong>Cinema Name:</strong> {{ $book_ticket_detail->name_cinema }}</p>
    <p><strong>Cinema Address:</strong> {{ $book_ticket_detail->address }}</p>
    <p><strong>Date:</strong> {{ $book_ticket_detail->date }}</p>
    <p><strong>Showtime:</strong> {{ $book_ticket_detail->time_suatchieu }}</p>
    <p><strong>Total Price:</strong> {{ $book_ticket_detail->total_price }}</p>
    <h2>Food Details:</h2>
    <ul>
            <li>{{ $book_ticket_detail->food_name }} - {{ $book_ticket_detail->food_price}}</li>
    </ul>

    <h2>Chair Details:</h2>
    <ul>
            <li>{{ $chair }}</li>
    </ul>
    <hr>
    
    @endforeach
</body>
</html>
