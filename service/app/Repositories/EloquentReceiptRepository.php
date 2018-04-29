<?php

namespace App\Repositories;

use App\Models\Receipt;
use App\Repositories\Contracts\ReceiptRepository;

class EloquentReceiptRepository extends AbstractEloquentRepository implements ReceiptRepository
{
    /**
     * Model name.
     *
     * @var string
     */
    protected $modelName = Receipt::class;
}
