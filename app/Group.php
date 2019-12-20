<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
		protected $fillable = ['user_id', 'nama_kelas'];
		protected $hidden   = ['created_at', 'updated_at'];

		public function wali_kelas()
		{
			return $this->hasOne('App\User', 'id', 'user_id');
		}

		public function jumlah_siswa()
		{
			return $this->hasMany('App\Student', 'group_id', 'id');
		}
}
