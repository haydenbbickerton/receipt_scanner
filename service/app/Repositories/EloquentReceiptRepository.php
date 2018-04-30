<?php

namespace App\Repositories;

use App\Models\Receipt;
use App\Repositories\Contracts\ReceiptRepository;
use App\Events\ReceiptEvents\ReceiptCreated;

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

        $newName = str_random('16');
        $receipt
           ->addMedia($filePath)
           ->usingName($newName)
           ->usingFileName($newName.'.'.$fileExt)
           ->toMediaCollection('receipts');

        \Event::fire(new ReceiptCreated($receipt));

        return $receipt;
    }
}
