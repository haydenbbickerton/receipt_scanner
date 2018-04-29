<?php

namespace App\Events\ReceiptEvents;

use App\Events\Event;
use App\Models\Receipt;

class ReceiptCreatedEvent extends Event
{
    /**
     * Instance of Receipt
     *
     * @var Receipt
     */
    public $receipt;

    /**
     * @param Receipt $receipt
     */
    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }
}