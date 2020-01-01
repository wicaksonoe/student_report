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
		$key = 0;
		$data = [];
		$semesters = Semester::all();

		$nama_semester = [
			'Mid-semester Ganjil',
			'Semester Ganjil',
			'Mid-semester Genap',
			'Semester Genap'
		];

		foreach ($semesters as $semester) {
			foreach ($nama_semester as $value) {
				$data[$key]['keterangan'] = $value;
				$data[$key]['tahun_akademik'] = $semester->tahun_akademik;
				$key++;
			}
		}

		// return $data;
		return DataTables::of($data)->make(true);
	}

	public function store(Request $request)
	{
		$validatedData = $request->validate([
			'tahun_akademik' => [
				'required',
				'regex:/\d\d\d\d\/\d\d\d\d$/',
				'unique:semesters,tahun_akademik'
			]
		]);

		Semester::create([
			'tahun_akademik' => $request->tahun_akademik
		]);

		return json_encode([
			'status' => 'success',
			'message' => 'Tahun akademik baru berhasil dibuat',
		]);
	}
}
