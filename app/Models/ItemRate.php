<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'rate'
    ];

    function getItemName(){
        return $this->belongsTo(Items::class, "item_id");
    }

}
