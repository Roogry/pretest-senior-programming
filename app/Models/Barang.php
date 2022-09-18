<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = "tb_barang";
    protected $guarded = [];
    protected $primaryKey = "id_barang";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'id_jenis');
    }

    public function penjualanDetail()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_barang');
    }
}
