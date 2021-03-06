<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;


class Receipt extends Model implements HasMedia
{

    use HasMediaTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'receipts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'userId',
        'name',
        'notes',
        'category',
        'totalAmount',
        'taxAmount',
        'merchantName',
        'date',
        'data',     // JSON returned by the Taggun API
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'userId'        => 'integer',
        'data'          => 'array',
        'totalAmount'   => 'float',
        'taxAmount'     => 'float',
    ];

    /**
     * Get the user that owns the receipt.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'userId');
    }
}
