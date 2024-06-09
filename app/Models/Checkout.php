<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checkout extends Model
{
    use HasFactory;

    protected $table = 'checkout'; // Nama tabel division

    protected $primaryKey = 'id_checkout'; // Nama kolom primary key

    protected $fillable = [
        'metode_kirim',
        'metode_bayar',
        'biaya_kirim',
        'total_pembayaran',
        'alamat',
        'id_customer',
       
    ];

    public function keranjang(): HasMany
    {
        return $this->hasMany(Keranjang::class, 'id_checkout', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_customer');
    }
}
