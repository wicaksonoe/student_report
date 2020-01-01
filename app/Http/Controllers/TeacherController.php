<?php

namespace App\Http\Controllers;

use App\Course;
use App\User;
use App\Teacher;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TeacherController extends Controller
{

	public function data($method = 'course_id')
	{
		if ($method == 'course_id' || $method == 'user_id') {
			$daftar_guru = User::where('role', 'guru')->get();
			$daftar_matpel = Course::all();

			switch ($method) {
				case 'user_id':
					return DataTables::of($daftar_guru)
						->addColumn('nama_guru', function ($daftar_guru) {
							return $daftar_guru->name;
						})
						->addColumn('action', function ($daftar_guru) {
							return '<button class="btn btn-sm btn-primary" onclick="edit(this)" value="' . route('teacher.edit', ['method' => 'user_id', 'id' => $daftar_guru->id]) . '">View & Edit</button>';
						})
						->rawColumns(['action'])
						->make(true);
					break;

				case 'course_id':
					return DataTables::of($daftar_matpel)
						->addColumn('mata_pelajaran', function ($daftar_matpel) {
							return $daftar_matpel->nama_matpel;
						})
						->addColumn('action', function ($daftar_matpel) {
							return '<button class="btn btn-sm btn-primary" onclick="edit(this)" value="' . route('teacher.edit', ['method' => 'course_id', 'id' => $daftar_matpel->id]) . '">View & Edit</button>';
						})
						->rawColumns(['action'])
						->make(true);
					break;
			}
		} else {
			return abort(404);
		}
	}

	public function message($method, $id)
	{
		if ($method == 'course_id' || $method == 'user_id') {
			switch ($method) {
				case 'course_id':
					$mata_pelajaran = Course::where('id', $id)->first();
					return json_encode([
						'status' => 'success',
						'message' => 'Daftar Guru Untuk Mata Pelajaran ' . $mata_pelajaran->nama_matpel
					]);
					break;

				case 'user_id':
					$nama_guru = User::where('id', $id)->first();
					return json_encode([
						'status' => 'success',
						'message' => 'Daftar Mata Pelajaran Untuk ' . $nama_guru->name
					]);
					break;
			}
		} else {
			return abort(404);
		}
	}

	public function index()
	{
		$daftar_guru = User::where('role', 'guru')->get();
		$daftar_mata_pelajaran = Course::all();

		return view('pengurus.guru', compact('daftar_guru', 'daftar_mata_pelajaran'));
	}

	public function store(Request $request)
	{
		$validateData = $request->validate([
			'user_id'   => 'required|numeric',
			'course_id' => 'required|numeric',
		]);

		$checking = Teacher::where([
			'user_id'   => $request->user_id,
			'course_id' => $request->course_id,
		])->first();

		if ($checking) {
			return abort(422, 'Guru sudah mengajar mata pelajaran ini.');
		}

		Teacher::create($request->all());

		$mata_pelajaran = Course::where('id', $request->course_id)->first();

		return json_encode([
			'status' => 'success',
			'message' => 'Guru mata pelajaran ' . $mata_pelajaran->nama_matpel . ' berhasil ditambahkan'
		]);
	}

	public function edit($method, $id)
	{
		if ($method == 'course_id' || $method == 'user_id') {
			switch ($method) {
				case 'course_id':
					$daftar = Teacher::where('course_id', $id);
					return DataTables::of($daftar)
						->addColumn('daftar', function ($daftar) {
							return $daftar->guru->name;
						})
						->addColumn('action', function ($daftar) {
							return '<button class="btn btn-sm btn-danger" onclick="destroy(this)" value="' . route('teacher.destroy', $daftar->id) . '">Hapus</button>';
						})
						->rawColumns(['action'])
						->make(true);
					break;

				case 'user_id':
					$daftar = Teacher::where('user_id', $id);
					return DataTables::of($daftar)
						->addColumn('daftar', function ($daftar) {
							return $daftar->mata_pelajaran->nama_matpel;
						})
						->addColumn('action', function ($daftar) {
							return '<button class="btn btn-sm btn-danger" onclick="destroy(this)" value="' . route('teacher.destroy', $daftar->id) . '">Hapus</button>';
						})
						->rawColumns(['action'])
						->make(true);
					break;
			}
		} else {
			return abort(404);
		}
	}

	public function destroy(Teacher $id)
	{
		$id->delete();

		return json_encode([
			'status' => 'success',
			'message' => 'Data berhasil dihapus'
		]);
	}
}
