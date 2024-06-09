<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ukuran extends Model
{
    use HasFactory;

    protected $table = 'ukuran';

    protected $primaryKey = 'id_ukuran';

    protected $fillable = [
        'ukuran',
    ];
    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'id_ukuran');
    }
    public function detailProducts(): HasMany
    {
        return $this->hasMany(DetailProduct::class, 'id_ukuran', 'id_ukuran');
    }
}
