<?php

namespace App\Transformers;

use App\Models\Receipt;
use League\Fractal\TransformerAbstract;

class ReceiptTransformer extends TransformerAbstract
{
    public function transform(Receipt $receipt)
    {
        return [
            'id'        => $receipt->uid,
            'userId'    => $receipt->userId,
            'name'      => $receipt->name,
            'notes'     => $receipt->notes,
            'category'  => $receipt->category,
            'data'      => $receipt->data,
            'mediaUrl'  => $receipt->getFirstMediaUrl("receipts"),
            'createdAt' => (string) $receipt->created_at,
            'updatedAt' => (string) $receipt->updated_at,
        ];
    }
}
