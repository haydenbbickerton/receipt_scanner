<?php

namespace App\Jobs;

use App\Models\Receipt;
use App\Events\ReceiptEvents\ReceiptProcessed;

class ProcessReceiptJob extends Job
{
    protected $receipt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $image = $this->receipt->getFirstMedia("receipts");

        $data = $this->sendToTaggun($image->getPath(), $image->mime_type);
        $data = json_decode($data, true);

        $this->receipt->update([
            'date'              => array_get($data, 'date.data', null),
            'merchantName'      => array_get($data, 'merchantName.data', null),
            'taxAmount'         => array_get($data, 'taxAmount.data', null),
            'totalAmount'       => array_get($data, 'totalAmount.data', null),
        ]);

        $saved = $this->receipt->save();

        // TODO: Handle errors in the processing and saving
        if ($saved) {
            event(new ReceiptProcessed($this->receipt));
        }
    }

    public function sendToTaggun($filePath, $mimeType, $verbose = false)
    {
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
