<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailProduct extends Model
{
    use HasFactory;

    protected $table = 'detail_product';

    protected $fillable = [
        'id_product',
        'id_category',
        'id_ukuran',
       
    ];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }
    public function ukuran()
    {
        return $this->belongsTo(Ukuran::class, 'id_ukuran', 'id_ukuran');
    }
}
