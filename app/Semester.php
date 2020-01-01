<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
	protected $fillable = ['tahun_akademik'];
	protected $hidden = ['created_at', 'updated_at'];
}
