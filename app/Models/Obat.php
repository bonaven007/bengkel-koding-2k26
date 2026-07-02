<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';

    protected $fillable = [
        'nama_obat',
        'kemasan',
        'harga',
        'stok',
    ];

    public function detailPeriksas()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }

    public function isOutOfStock(): bool
    {
        return $this->stok <= 0;
    }

    public function isLowStock(): bool
    {
        return $this->stok > 0 && $this->stok <= 5;
    }

    public function reduceStock(int $amount = 1): bool
    {
        if ($amount <= 0 || $this->stok < $amount) {
            return false;
        }

        $this->decrement('stok', $amount);
        $this->refresh();

        return true;
    }

    public function increaseStock(int $amount = 1): bool
    {
        if ($amount <= 0) {
            return false;
        }

        $this->increment('stok', $amount);
        $this->refresh();

        return true;
    }
}