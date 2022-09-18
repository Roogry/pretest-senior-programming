<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    use HasFactory;

    protected $table = "tb_jenis";
    protected $primaryKey = "id_jenis";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_jenis');
    }
}
