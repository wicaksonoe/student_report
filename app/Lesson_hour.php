<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson_hour extends Model
{
	protected $fillable = ['sesi', 'waktu'];
	protected $hidden = ['created_at', 'updated_at'];
}
