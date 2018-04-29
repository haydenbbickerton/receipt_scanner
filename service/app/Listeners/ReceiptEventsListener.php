<?php

namespace App\Listeners;

use Illuminate\Events\Dispatcher;
use App\Events\ReceiptEvents\ReceiptCreatedEvent;
// use App\Mails\WelcomeEmail;
// use Illuminate\Support\Facades\Mail;

class ReceiptEventsListener
{
    /**
     * Handle the receipt created event
     *
     * @param ReceiptCreatedEvent $event
     */
    public function onReceiptCreatedEvent($event)
    {
        $receipt = $event->receipt;

        //send welcome email to the receipt
        // Mail::to($receipt)->send(new WelcomeEmail($receipt));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            ReceiptCreatedEvent::class,
            'App\Listeners\ReceiptEventsListener@onReceiptCreatedEvent'
        );
    }
}