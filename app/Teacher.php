<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
	protected $fillable = ['user_id', 'course_id', 'group_id'];
	protected $hidden   = ['created_at', 'updated_at'];

	public function guru()
	{
		return $this->hasOne('App\User', 'id', 'user_id');
	}

	public function mata_pelajaran()
	{
		return $this->hasOne('App\Course', 'id', 'course_id');
	}

	public function kelas()
	{
		return $this->hasOne('App\Group', 'id', 'group_id');
	}
}
