<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
	protected $fillable = ['group_id', 'semester_id', 'lesson_hour_id', 'teacher_id', 'hari'];
	protected $hidden = ['created_at', 'updated_at'];

	public function kelas()
	{
		return $this->hasOne('App\Group', 'id', 'group_id');
	}

	public function semester()
	{
		return $this->hasOne('App\Semester', 'id', 'semester_id');
	}

	public function jam_pelajaran()
	{
		return $this->hasOne('App\Lesson_hour', 'id', 'lesson_hour_id');
	}

	public function pelajaran()
	{
		return $this->hasOne('App\Teacher', 'id', 'teacher_id');
	}
}
