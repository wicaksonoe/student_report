<?php

namespace App\Http\Controllers;

use App\Course;
use App\Group;
use App\Lesson_hour;
use App\Schedule;
use App\Semester;
use App\Teacher;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ScheduleController extends Controller
{
	private function convert_hari($id)
	{
		switch ($id) {
			case 1:
				return 'Senin';
				
			case 2:
				return 'Selasa';

			case 3:
				return 'Rabu';

			case 4:
				return 'Kamis';

			case 5:
				return 'Jumat';

			case 6:
				return 'Sabtu';

			case 7:
				return 'Minggu';
		}
	}

	public function data(Request $request)
	{
		$daftar_jadwal = [];

		if ($request->group_id == 'all') {
			$data_jadwal = Schedule::orderBy('group_id', 'ASC')
											->orderBy('semester_id', 'ASC')
											->orderBy('hari', 'ASC')
											->orderBy('lesson_hour_id', 'ASC')
											->get();
		} elseif ($request->group_id != 'all'){
			$data_jadwal = Schedule::where('group_id', $request->group_id)
											->orderBy('group_id', 'ASC')
											->orderBy('semester_id', 'ASC')
											->orderBy('hari', 'ASC')
											->orderBy('lesson_hour_id', 'ASC')
											->get();
		}

		foreach ($data_jadwal as $key => $val) {
			$daftar_jadwal[$key]['id']             = $val->id;
			$daftar_jadwal[$key]['kelas']          = $val->kelas->nama_kelas;
			$daftar_jadwal[$key]['tahun_akademik'] = $val->semester->tahun_akademik;
			$daftar_jadwal[$key]['hari']           = $this->convert_hari($val->hari);
			$daftar_jadwal[$key]['waktu']          = $val->jam_pelajaran->waktu;
			$daftar_jadwal[$key]['mata_pelajaran'] = $val->pelajaran->mata_pelajaran->nama_matpel;
			$daftar_jadwal[$key]['guru']           = $val->pelajaran->guru->name;
		}

		return DataTables::of($daftar_jadwal)
			->addColumn('action', function($val) {
				return '
					<button class="btn btn-sm btn-info" value="'. route('jadwal.kelas.edit', $val['id']) . '" onclick="edit(this)">Edit</button>
					<button class="btn btn-sm btn-danger" value="'. route('jadwal.kelas.destroy', $val['id']) . '" style="margin-left: 2rem" onclick="destroy(this)">Delete</button>
				';
			})
			->rawColumns(['action'])
			->make(true);
	}

	public function data_guru(Request $request)
	{
		$daftar_guru = [];
		$guru = Teacher::where('course_id', $request->id)->get();

		foreach ($guru as $key => $val) {
			$daftar_guru[$key]['id'] = $val->id;
			$daftar_guru[$key]['nama_guru'] = $val->guru->name;
		}
		
		return json_encode([
			'message' => 'success',
			'data' => $daftar_guru
		]);
	}

	public function index()
	{
		$kelas = Group::all(['id', 'nama_kelas']);
		$semester = Semester::all();
		$waktu = Lesson_hour::all();
		$mata_pelajaran = Course::all(['id', 'nama_matpel']);

		return view('pengurus.jadwal_pelajaran', compact('kelas', 'semester', 'waktu', 'mata_pelajaran'));
	}

	public function store(Request $request)
	{
		$validateData = $request->validate([
			'group_id'       => 'required|numeric',
			'semester_id'    => 'required|numeric',
			'lesson_hour_id' => 'required|numeric',
			'course_id'      => 'required|numeric',
			'teacher_id'     => 'required|numeric',
			'hari'           => 'required|numeric',
		]);

		$data_is_exist = Schedule::where('teacher_id', $request->teacher_id)
												->where('hari', $request->hari)
												->where('semester_id', $request->semester_id)
												->where('lesson_hour_id', $request->lesson_hour_id)
												->first();

		if ($data_is_exist) {
			return abort(422, 'Gagal memproses data. Guru sudah memiliki jadwal di jam ini.');
		}

		$data_is_exist = Schedule::where('group_id', $request->group_id)
												->where('hari', $request->hari)
												->where('semester_id', $request->semester_id)
												->where('lesson_hour_id', $request->lesson_hour_id)
												->first();

		if ($data_is_exist) {
			return abort(422, 'Gagal memproses data. Kelas sudah memiliki jadwal di jam ini.');
		}

		Schedule::create([
			'group_id'       => $request->group_id,
			'semester_id'    => $request->semester_id,
			'lesson_hour_id' => $request->lesson_hour_id,
			'teacher_id'      => $request->teacher_id,
			'hari'           => $request->hari,
		]);

		return json_encode([
			'message' => 'success',
			'message' => 'Jadwal baru berhasil dibuat'
		]);
	}

	public function edit(Schedule $id)
	{
		$daftar_guru = [];
		$guru = Teacher::where('course_id', $id->pelajaran->mata_pelajaran->id)->get();

		foreach ($guru as $key => $val) {
			$daftar_guru[$key]['id'] = $val->id;
			$daftar_guru[$key]['nama_guru'] = $val->guru->name;
		}

		$data = [
			"id"             => $id->id,
			"group_id"       => $id->group_id,
			"semester_id"    => $id->semester_id,
			"lesson_hour_id" => $id->lesson_hour_id,
			"hari"           => $id->hari,
			"course_id"       => $id->pelajaran->mata_pelajaran->id,
			"teacher_id"		 => $id->teacher_id,
			"daftar_guru"		 => $daftar_guru,
		];

		return $data;
	}

	public function update(Request $request)
	{
		$validateData = $request->validate([
			'id'             => 'required|numeric',
			'group_id'       => 'required|numeric',
			'semester_id'    => 'required|numeric',
			'lesson_hour_id' => 'required|numeric',
			'course_id'      => 'required|numeric',
			'teacher_id'     => 'required|numeric',
			'hari'           => 'required|numeric',
		]);

		$data_is_exist = Schedule::where('teacher_id', $request->teacher_id)
												->where('hari', $request->hari)
												->where('semester_id', $request->semester_id)
												->where('lesson_hour_id', $request->lesson_hour_id)
												->first();

		if ($data_is_exist) {
			return abort(422, 'Gagal memproses data. Guru sudah memiliki jadwal di jam ini.');
		}

		Schedule::where('id', $request->id)
			->update([
				'group_id'       => $request->group_id,
				'semester_id'    => $request->semester_id,
				'lesson_hour_id' => $request->lesson_hour_id,
				'teacher_id'     => $request->teacher_id,
				'hari'           => $request->hari,
			]);

		return json_encode([
			'message' => 'success',
			'message' => 'Jadwal berhasil dirubah.'
		]);
	}

	public function destroy(Schedule $id)
	{
		$id->delete();

		return json_encode([
			'status' => 'success',
			'message' => 'Jadwal pelajaran berhasil dihapus.'
		]);
	}
}
