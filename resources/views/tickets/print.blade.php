<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'DejaVu Sans',sans-serif;
        }

        h1, h2 {
            color: #333;
        }

        span {
            display: block;
            color: #555;
        }

        strong {
            font-weight: bold;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        @media print {
            body {
                font-size: 12pt;
            }
        }
    </style>
</head>
<body>

@foreach($array_chair as $key => $chair)
    <h1>Ticket</h1>
    <h1>Chair Details: {{ $chair }}</h1>
    <span><strong>Booking time:</strong> {{ $book_ticket_detail->time }}</span>
    <span><strong>Film Name:</strong> {{ $book_ticket_detail->name }}</span>
    <span><strong>Ticket Code:</strong>...{{ substr($book_ticket_detail->id_code, -7) }}</span>
    <span><strong>Movie Room Name:</strong> {{ $book_ticket_detail->movie_room_name }}</span>
    <span><strong>Cinema Name:</strong> {{ $book_ticket_detail->name_cinema }}</span>
    <span><strong>Cinema Address:</strong> {{ $book_ticket_detail->address }}</span>
    <span><strong>Date:</strong> {{ $book_ticket_detail->date }}</span>
    <span><strong>Showtime:</strong> {{ $book_ticket_detail->time_suatchieu }}</span>
    <span><strong>Total Price:</strong> {{ number_format($book_ticket_detail->total_price, 0, ',', '.') }} VND</span>
    {{-- Add some space after each ticket, but not after the last one --}}
    @if (!$loop->last)
        <div style="margin-bottom: 10px;"></div>
    @endif
    <div style="page-break-after: always;"></div>
@endforeach

<div style="margin-bottom: 10px;"></div>
<hr>
<h2>Food Details:</h2>
<ul>
    @foreach ($food_ticket_detail as $key => $food_detail)
        <li>{{ $food_detail->quantity }} * {{ $food_detail->name }}</li>
    @endforeach
    <span><strong>Date:</strong> {{ $book_ticket_detail->date }}</span>
        <span><strong>Cinema Address:</strong> {{ $book_ticket_detail->address }}</span>
</ul>


</body>
</html>
