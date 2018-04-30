<?php

namespace App\Listeners;

use App\Models\Receipt;
use App\Events\ReceiptEvents\ReceiptCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Unirest\Request;


class ProcessReceipt implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(ReceiptCreated $event)
    {
       $receipt = $event->receipt;
       $image = $receipt->getFirstMedia("receipts");

       $data = $this->sendToTaggun($image->getPath(), $image->mime_type);

       $receipt->data = $data;
       $receipt->save();

    }

    public function sendToTaggun($filePath, $mimeType, $verbose = false) {

        $url = 'https://api.taggun.io/api/receipt/v1/' . ($verbose ? 'verbose/file' : 'simple/file');

        $headers = [
            'Accept'    =>  'application/json',
            'apikey'    =>  config('taggun.apiKey')
        ];

        $body = array(
            'file' => \Unirest\Request\Body::file($filePath, $mimeType)
        );

        $response = \Unirest\Request::post($url, $headers, $body);

        return $response->body;
    }
}
