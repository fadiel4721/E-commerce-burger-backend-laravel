<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjang';

    protected $primaryKey = 'id_keranjang';
    protected $fillable = [
        'qty', 'harga_satuan', 'total_harga', 'id_product', 'id_customer','id_checkout',

    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_customer');
    }
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
    public function checkout() : BelongsTo
    {
        return $this->belongsTo(Checkout::class, 'id_checkout', 'id_checkout');
    }
}
