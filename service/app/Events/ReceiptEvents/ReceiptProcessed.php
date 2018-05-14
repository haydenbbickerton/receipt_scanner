<?php

namespace App\Events\ReceiptEvents;

use App\Events\Event;
use App\Models\Receipt;
use App\Transformers\ReceiptTransformer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;

class ReceiptProcessed extends Event implements ShouldBroadcast
{
    use InteractsWithSockets;

    /**
     * Instance of Receipt
     *
     * @var Receipt
     */
    public $receipt;
    private $fractal;

    /**
     * @param Receipt $receipt
     */
    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return new Channel('riskgenius');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'receipt.processed';
    }

    /**
     * We'll use our receipt transformer to transform the return data
     *
     * @return object
     */
    public function broadcastWith()
    {
        $fractal = new Manager();
        $resource = new Item($this->receipt, new ReceiptTransformer());
        $array = $fractal->createData($resource)->toArray();
        return $array['data'];
    }
}