<?php

namespace App\Transformers;

use App\Models\Receipt;
use League\Fractal\TransformerAbstract;

class ReceiptTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'rawData'
    ];

    public function transform(Receipt $receipt)
    {
        return [
            'id'                => $receipt->uid,
            'userId'            => $receipt->userId,
            'name'              => $receipt->name,
            'notes'             => $receipt->notes,
            'category'          => $receipt->category,
            'date'              => $receipt->date,
            'merchantName'      => $receipt->merchantName,
            'taxAmount'         => $receipt->taxAmount,
            'totalAmount'       => $receipt->totalAmount,
            'mediaUrl'          => $receipt->getFirstMediaUrl("receipts"),
            'createdAt'         => (string) $receipt->created_at,
            'updatedAt'         => (string) $receipt->updated_at,
        ];
    }

    /**
     * Include Raw data
     *
     */
    public function includeRawData(Receipt $receipt)
    {
        return $receipt->data;
    }
}
