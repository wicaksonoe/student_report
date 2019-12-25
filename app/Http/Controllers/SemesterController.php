<?php

namespace App\Http\Controllers;

use App\Semester;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SemesterController extends Controller
{
	public function index()
	{
		return view('pengurus.semester');
	}

	public function data()
	{
		$semesters = Semester::all();

		return DataTables::of($semesters)->make(true);
	}

	public function store(Request $request)
	{
		$validatedData = $request->validate([
			'tahun_akademik' => 
				array(
					'required',
					'regex:/\d\d\d\d\/\d\d\d\d$/',
					'unique:semesters,tahun_akademik'
				)
		]);

		$semester = [
			'Mid-semester Ganjil',
			'Semester Ganjil',
			'Mid-semester Genap',
			'Semester Genap'
		];

		foreach ($semester as $val) {
			Semester::create([
				'keterangan' => $val,
				'tahun_akademik' => $request->tahun_akademik
			]);
		}

		return json_encode([
			'status' => 'success',
			'message' => 'Tahun akademik baru berhasil dibuat',
		]);
	}
}
