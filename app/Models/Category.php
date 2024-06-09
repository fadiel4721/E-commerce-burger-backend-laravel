<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    public $table = 'categories'; // Nama tabel division

    protected $primaryKey = 'id_category'; // Nama kolom primary key

    protected $fillable = [
        'nama_category',
    ];
     public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'id_category');
    }

    public function detailProducts():HasMany
    {
        return $this->hasMany(DetailProduct::class, 'id_category', 'id_category');
    }
}
