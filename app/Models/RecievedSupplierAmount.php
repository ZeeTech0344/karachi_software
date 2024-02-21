<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecievedSupplierAmount extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'supplier_id',
        'amount',
        'remarks',
        'amount_status'
      
    ];

    function getSupplierInfo(){
        return $this->belongsTo(BuyerPurchaserDetail::class, "supplier_id");
    }


}
