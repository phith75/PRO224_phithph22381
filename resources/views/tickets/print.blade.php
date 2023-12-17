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
  
    @foreach($array_chair as $key => $chair)
    <h1>Ticket</h1>
    <h1>Chair Details: {{$chair}}</h1>
    
    <p><strong>Booking time:</strong> {{ $book_ticket_detail->time }}</p>
    <p><strong>Film Name:</strong> {{ $book_ticket_detail->name }}</p>
    <p><strong>Ticket Code:</strong>...{{  substr($book_ticket_detail->id_code, -7) }}</p>
    <p><strong>Movie Room Name:</strong> {{ $book_ticket_detail->movie_room_name }}</p>
    <p><strong>Cinema Name:</strong> {{ $book_ticket_detail->name_cinema }}</p>
    <p><strong>Cinema Address:</strong> {{ $book_ticket_detail->address }}</p>
    <p><strong>Date:</strong> {{ $book_ticket_detail->date }}</p>
    <p><strong>Showtime:</strong> {{ $book_ticket_detail->time_suatchieu }}</p>
    <p><strong>Total Price:</strong> {{ number_format($book_ticket_detail->total_price, 0, ',', '.') }} VND</p>
    <h2>Food Details:</h2>
    <ul>
    @foreach ($food_ticket_detail as $key => $food_detail)  
   <li> {{$food_detail->quantity}} *  {{$food_detail->name}}</li>
    
@endforeach
    </ul>
           
    <hr> 
    {{-- Tách trang mỗi khi end 1 vé --}}
    @if (!$loop->last) 
    <div style="page-break-after: always;"></div>
@endif
    @endforeach
</body>
</html>
