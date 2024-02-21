<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class supplierData extends Model
{
    use HasFactory;
     
    protected $fillable = [
        'supplier_id',
        'head',
        'quantity',
        'amount',
        'total',
        'quantity',
        'amount_status'
      
    ];

    function getSupplierInfo(){
        return $this->belongsTo(BuyerPurchaserDetail::class, "supplier_id");
    }


}
