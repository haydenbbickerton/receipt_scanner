<?php

namespace App\Repositories;

use App\Models\Receipt;
use App\Repositories\Contracts\ReceiptRepository;
use App\Events\ReceiptEvents\ReceiptCreatedEvent;

class EloquentReceiptRepository extends AbstractEloquentRepository implements ReceiptRepository
{
    /**
     * Model name.
     *
     * @var string
     */
    protected $modelName = Receipt::class;

    /*
     * @inheritdoc
     */
    public function save(array $data)
    {
        $fileExt = array_pull($data, 'fileExt');
        $filePath = array_pull($data, 'filePath');
        $receipt = parent::save($data);


        $receipt
           ->addMedia($filePath)
           ->usingFileName(str_random('16').'.'.$fileExt)
           ->toMediaCollection();

        \Event::fire(new ReceiptCreatedEvent($receipt));

        return $receipt;
    }
}
