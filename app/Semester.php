<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
	protected $fillable = ['keterangan', 'tahun_akademik'];
	protected $hidden = ['created_at', 'updated_at'];
}
