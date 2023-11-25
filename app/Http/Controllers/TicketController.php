<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class TicketController extends Controller
{
    public function printTicket($ticketId)
    {
        // Logic để lấy thông tin vé từ $ticketId

        $ticketData = [
            'ticket_id' => $ticketId,
            'name' => 'John Doe', // Thông tin khác về vé
            // ...
        ];

        $pdf = PDF::loadView('tickets.print', compact('ticketData'));

        // Gửi PDF cho trình duyệt để hiển thị hoặc tải xuống
        return $pdf->stream('ticket.pdf');
    }
}
