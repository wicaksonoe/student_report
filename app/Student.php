<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
	protected $fillable = [
		'nis',
		'group_id',
		'name',
		'jenis_kelamin',
		'tahun_masuk',
		'tempat_lahir',
		'tgl_lahir',
		'nama_ayah',
		'nama_ibu',
		'no_hp_ortu',
		'alamat',
		'no_hp',
		'photo',
	];

	protected $hidden = [
		'created_at',
		'updated_at',
	];

	public function kelas()
	{
		return $this->hasOne('App\Group', 'id', 'group_id');
	}
}
