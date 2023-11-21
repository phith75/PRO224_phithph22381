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
    protected $latestTicket;

    public function __construct($latestTicket)
        {
            $this->latestTicket = $latestTicket;
        }
    use Queueable, SerializesModels;
    public function build()
    {
        $currentUser = Auth::user();
        $latestTicket = Book_ticket::where('user_id', $currentUser->id)
            ->latest('created_at')
            ->first();
    
        if (!$latestTicket) {
            return $this->subject('Thông tin đặt vé xem film - mã thanh toán: Chưa có vé')
                ->markdown('emails.book_ticket_details', ['bookTicketDetails' => null]);
        }
    
        return $this->subject('Thông tin đặt vé xem film - mã thanh toán: ' . $latestTicket->id_code)
            ->markdown('emails.book_ticket_details', ['bookTicketDetails' => [$latestTicket]]);
    }
    
    
}
