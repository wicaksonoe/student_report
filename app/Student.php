<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nis',
        'name',
        'tahun_masuk',
        'tempat_lahir',
        'tgl_lahir',
        'nama_ayah',
        'nama_ibu',
        'no_hp_ortu',
        'alamat',
        'no_hp',
        'photo',
    ];
}
