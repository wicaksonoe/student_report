<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
  protected $fillable = [
		'student_id',
		'semester_id',
		'group_id',
		'semester',
		'teacher_id',
		'nilai',
	];

	protected $hidden = ['created_at', 'updated_at'];

	public function pelajaran()
	{
		return $this->hasOne('App\Teacher', 'id', 'teacher_id');
	}
	
	public function siswa()
	{
		return $this->hasOne('App\Student', 'id', 'student_id');
	}
	
	public function semester_table()
	{
		return $this->hasOne('App\Semester', 'id', 'semester_id');
	}
	
	public function group_table()
	{
		return $this->hasOne('App\Group', 'id', 'group_id');
	}
}
