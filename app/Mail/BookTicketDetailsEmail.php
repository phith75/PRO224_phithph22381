<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Book_ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;


class BookTicketDetailsEmail extends Mailable
{
    use Queueable, SerializesModels;
    public function build()
    {
        $currentUser = Auth::user();
        $bookTicketDetails = Book_ticket::where('user_id', $currentUser->id)->get();
        $idCodes = [];
        foreach ($bookTicketDetails as $ticket) {
            $idCodes[] = $ticket->id_code;
        }
        $idCodeString = implode(', ', $idCodes);
        return $this->subject('Thông tin đặt vé xem film - mã thanh toán: ' . $idCodeString)
            ->markdown('emails.book_ticket_details', ['bookTicketDetails' => $bookTicketDetails]);
    }
}
