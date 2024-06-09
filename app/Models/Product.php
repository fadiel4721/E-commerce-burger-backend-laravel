<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products'; // Nama tabel division

    protected $primaryKey = 'id_product'; // Nama kolom primary key


    public $incrementing = true; // Primary key menggunakan auto-increment
    protected $keyType = 'int'; // Tipe data primary key
    protected $fillable = [
        'nama_product',
        'description',
        'stock',
        'price',
        'image',
        'id_category',
        'id_ukuran',
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_category');
    }
    public function ukuran(): BelongsTo
    {
        return $this->belongsTo(Ukuran::class, 'id_ukuran');
    }
    public function keranjang(): HasMany
    {
        return $this->hasMany(Keranjang::class, 'id_product');
    }
    public function detailProducts(): HasMany
    {
        return $this->hasMany(DetailProduct::class, 'id_product', 'id_product');
    }
}
