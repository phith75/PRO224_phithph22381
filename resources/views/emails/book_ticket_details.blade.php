<!DOCTYPE html>
<html>
<head>
    <title>Chi tiết thông tin vé</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Passenger Name</th>
                <th>Departure Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookTicketDetails as $detail)
                <tr>
                    <td>{{ $detail->user_id }}</td>
                    <td>{{ $detail->id_time_detail }}</td>
                    <td>{{ $detail->payment }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
