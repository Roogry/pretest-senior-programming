<?php

namespace App\Models;

use App\Models\Pelanggan;
use App\Models\PenjualanDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = "tb_penjualan";
    protected $guarded = [];
    protected $primaryKey = "id_penjualan";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }
}
