<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemData extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'item_id',
        'old_rate',
        'qty_or_length',
        'scale',
        'total',
    ];

    function getItemName(){
        return $this->belongsTo(Items::class, "item_id");
    }

}
