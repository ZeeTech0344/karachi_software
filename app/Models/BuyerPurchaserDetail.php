<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerPurchaserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_no',
        'account_no',
        'cnic',
        'address',
        'status'
    ];

    function getSupplierData(){
        return $this->hasMany(supplierData::class, "supplier_id", "id");
    }


}
