<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cnpj',
        'company_name',
        'trading_name',
        'address',
        'city',
        'state',
        'zip_code',
        'phone',
        'whatsapp',
        'email',
        'contact_person',
        'supplier_category_id',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(SupplierCategory::class, 'supplier_category_id');
    }
}
