<?php

namespace App\Http\Controllers;

use App\Lesson_hour;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LessonHourController extends Controller
{
	public function data()
	{
		$data_jam_pelajaran = Lesson_hour::all();

		return DataTables::of($data_jam_pelajaran)
			->addColumn('action', function($data_jam_pelajaran) {
				return '<button class="btn btn-sm btn-danger" value="'. route('jadwal.jam.destroy', $data_jam_pelajaran->id) .'" onclick="destroy(this)">Hapus</button>';
			})
			->rawColumns(['action'])
			->make(true);
	}

	public function index()
	{
		return view('pengurus.jam_pelajaran');
	}

	public function store(Request $request)
	{
		$validateData = $request->validate([
			'sesi'        => 'required',
			'waktu_awal'  => array('required','regex:/\d\d.\d\d$/'),
			'waktu_akhir' => array('required','regex:/\d\d.\d\d$/')
		]);

		$data_already_available = Lesson_hour::where('waktu', $request->waktu_awal.' - '.$request->waktu_akhir)
																->where('sesi', $request->sesi)
																->first();

		if ($data_already_available) {
			return abort(422, 'Jam pelajaran sudah ada');
		}

		Lesson_hour::create([
			'sesi' => title_case($request->sesi),
			'waktu' => $request->waktu_awal.' - '.$request->waktu_akhir
		]);

		return json_encode([
			'status' => 'success',
			'message' => 'Jam pelajaran berhasil ditambahkan.'
		]);
	}

	public function destroy(Lesson_hour $id)
	{
		$id->delete();

		return json_encode([
			'status' => 'success',
			'message' => 'Jam pelajaran berhasil dihapus.'
		]);
	}
}
