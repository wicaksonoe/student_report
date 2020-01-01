<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance_report extends Model
{
		protected $fillable = [
			'schedule_id',
			'guru_pengganti',
			'jam_masuk',
			'jam_keluar',
			'pertemuan',
			'tgl_pertemuan',
			'pokok_bahasan',
			'tugas',
		];
		
		protected $casts = [
			'jam_masuk' => 'date:hh:mm',
			'jam_keluar' => 'date:hh:mm',
		];

		public function pengganti()
		{
			try {
				return $this->hasOne('App\User', 'id', 'guru_pengganti');
			} catch (\Throwable $th) {
				return null;
			}
		}
}
