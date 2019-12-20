<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
{
	public function index()
	{
		return view('pengurus.matpel');
	}

	public function data()
	{
		$mata_pelajaran = Course::all();

		return DataTables::of($mata_pelajaran)
			->addColumn('action', function($mata_pelajaran) {
				return '
					<button class="btn btn-sm btn-warning" style="margin: 1rem" value="'. route('course.edit', $mata_pelajaran->id) . '" onclick="edit(this)">Edit</button>
					<button class="btn btn-sm btn-danger"  style="margin: 1rem" value="'. route('course.destroy', $mata_pelajaran->id) . '" onclick="destroy(this)">Delete</button>
				';
			})
			->rawColumns(['action'])
			->make(true);
	}

	public function store(Request $request)
	{
		$validateData = $request->validate([
			'kategori_matpel' => 'required',
			'nama_matpel'     => 'required|unique:courses,nama_matpel',
			'kkm'             => 'required|numeric|max:100|min:0'
		]);

		$kategori_matpel = title_case($request->kategori_matpel);
		$nama_matpel     = title_case($request->nama_matpel);
		$kkm             = title_case($request->kkm);

		Course::create([
			'kategori_matpel' => $kategori_matpel,
			'nama_matpel'     => $nama_matpel,
			'kkm'             => $kkm,
		]);

		return json_encode([
			'status' => 'success',
			'message' => 'Mata Pelajaran Baru Berhasil Dimasukan',
		]);
	}

	public function edit(Course $id)
	{
		return json_encode($id);
	}

	public function update(Request $request)
	{
		$validateData = $request->validate([
			'id'              => 'required',
			'kategori_matpel' => 'required',
			'nama_matpel'     => 'required',
			'kkm'             => 'required|numeric|max:100|min:0'
		]);

		$kategori_matpel = title_case($request->kategori_matpel);
		$nama_matpel     = title_case($request->nama_matpel);
		$kkm             = title_case($request->kkm);

		Course::where('id', $request->id)
			->update([
				'kategori_matpel' => $kategori_matpel,
				'nama_matpel'     => $nama_matpel,
				'kkm'             => $kkm,
			]);

		return json_encode([
			'status' => 'success',
			'message' => 'Mata Pelajaran '.$nama_matpel.' Berhasil Diubah',
		]);
	}

	public function destroy(Course $id)
	{
		$id->delete();

		return json_encode([
			'status' => 'success',
			'message' => 'Mata Pelajaran Berhasil Dihapus',
		]);
	}
}
