<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['kategori_matpel', 'nama_matpel', 'kkm'];
		protected $hidden = ['created_at', 'updated_at'];
}
