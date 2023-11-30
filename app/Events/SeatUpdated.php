// app/Events/SeatUpdated.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SeatUpdated implements ShouldBroadcast
{
use Dispatchable, InteractsWithSockets, SerializesModels;

public $seatData;

public function __construct($seatData)
{
$this->seatData = $seatData;
}

public function broadcastOn()
{
return new Channel('seat-reservation');
}
}