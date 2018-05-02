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
        $data = json_decode($data, true);

        $receipt->update([
            'date'              => array_get($data, 'date.data', null),
            'merchantName'      => array_get($data, 'merchantName.data', null),
            'taxAmount'         => array_get($data, 'taxAmount.data', null),
            'totalAmount'       => array_get($data, 'totalAmount.data', null),
        ]);
        $receipt->save();
    }

    public function sendToTaggun($filePath, $mimeType, $verbose = false) {

        $url = config('taggun.api.url') . ($verbose ? 'verbose/file' : 'simple/file');

        $headers = [
            'Accept'    =>  'application/json',
            'apikey'    =>  config('taggun.api.key')
        ];

        $body = array(
            'file' => \Unirest\Request\Body::file($filePath, $mimeType)
        );

        $response = \Unirest\Request::post($url, $headers, $body);

        return $response->raw_body;
    }
}
