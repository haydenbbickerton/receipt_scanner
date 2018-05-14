<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\ReceiptEvents\ReceiptCreated;
use App\Jobs\ProcessReceiptJob;

class ReceiptEventsListener implements ShouldQueue
{
    /**
     * Handle the receipt created event
     *
     * @param ReceiptCreated $event
     */
    public function onReceiptCreatedEvent($event)
    {
        $receipt = $event->receipt;

        dispatch(new ProcessReceiptJob($receipt));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            ReceiptCreated::class,
            'App\Listeners\ReceiptEventsListener@onReceiptCreatedEvent'
        );
    }
}