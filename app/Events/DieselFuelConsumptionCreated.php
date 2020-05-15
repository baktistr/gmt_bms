<?php

namespace App\Events;

use App\DieselFuelConsumption;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DieselFuelConsumptionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\DieselFuelConsumption
     */
    public $dieselFuelConsumption;

    /**
     * Create a new event instance.
     *
     * @param \App\DieselFuelConsumption $dieselFuelConsumption
     */
    public function __construct(DieselFuelConsumption $dieselFuelConsumption)
    {
        $this->dieselFuelConsumption = $dieselFuelConsumption;
    }
}
