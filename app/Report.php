<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
  protected $fillable = [
		'student_id',
		'group_id',
		'semester_id',
		'teacher_id',
		'nilai',
	];
}
