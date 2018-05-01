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
            'date'              => array_get($receipt->data, 'date.data', null),
            'merchantAddress'   => array_get($receipt->data, 'merchantAddress.data', null),
            'merchantName'      => array_get($receipt->data, 'merchantName.data', null),
            'merchantTypes'     => array_get($receipt->data, 'merchantTypes.data', null),
            'taxAmount'         => array_get($receipt->data, 'taxAmount.data', null),
            'totalAmount'       => array_get($receipt->data, 'totalAmount.data', null),
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
