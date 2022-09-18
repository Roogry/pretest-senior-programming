<?php

namespace App\Models;

use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = "tb_pelanggan";
    protected $primaryKey = "id_pelanggan";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'id_pelanggan');
    }
}
