<?php

namespace App\Http\Controllers;

use App\Attendance_report;
use App\Schedule;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AttendanceReportController extends Controller
{
	public function message(Schedule $schedule_id)
	{
		return response()->json([
			'message' => 'Notulensi '.$schedule_id->pelajaran->mata_pelajaran->nama_matpel
										.' Kelas '.$schedule_id->kelas->nama_kelas
										.' ('.$schedule_id->semester->tahun_akademik.')'
		]);
	}

	public function data_notulensi($schedule_id)
	{
		$data_notulensi = [];
		$notulensi = Attendance_report::where('schedule_id', $schedule_id)->get();

		foreach ($notulensi as $key => $value) {
			$data_notulensi[$key]['id']            = $value->id;
			$data_notulensi[$key]['pertemuan']     = $value->pertemuan;
			$data_notulensi[$key]['semester']     = $value->semester;
			$data_notulensi[$key]['tgl_pertemuan'] = date('d-M-Y', strtotime($value->tgl_pertemuan));
			$data_notulensi[$key]['pokok_bahasan'] = $value->pokok_bahasan;
			$data_notulensi[$key]['tugas']         = $value->tugas;
		}

		return DataTables::of($data_notulensi)
			->addColumn('bahasan', function ($data_notulensi) {
				return $data_notulensi['pokok_bahasan'] . '<br><br>Tugas:<br>' . $data_notulensi['tugas'];
			})
			->addColumn('action', function ($data_notulensi) {
				return '
					<button class="btn btn-sm btn-info" value="' . $data_notulensi['id'] . '" onclick="show_notulensi_detail(this.value)">Detail</button>
					<button class="btn btn-sm btn-warning" style="margin-left: 2rem;" value="' . $data_notulensi['id'] . '" onclick="show_notulensi_edit(this.value)">Ubah</button>
				';
			})
			->rawColumns(['bahasan', 'action'])
			->make(true);
	}

	public function data_matpel()
	{
		$data_matpel = [];
		$user = Auth::user();

		$jadwal = Schedule::all();

		foreach ($jadwal as $key => $value) {
			if ($value->pelajaran->user_id == $user->id) {
				$data_matpel[$key]['id'] = $value->id;
				$data_matpel[$key]['kelas'] = $value->kelas->nama_kelas;
				$data_matpel[$key]['matpel'] = $value->pelajaran->mata_pelajaran->nama_matpel;
				$data_matpel[$key]['semester'] = $value->semester->tahun_akademik;
			}
		}

		return DataTables::of($data_matpel)
			->addColumn('nama_matpel', function ($data_matpel) {
				return $data_matpel['matpel'].' ('.$data_matpel['semester'].')';
			})
			->addColumn('action', function ($data_matpel) {
				return '<button class="btn btn-sm btn-primary" value="' . $data_matpel['id'] . '" onclick="show_notulensi(this.value)">Lihat notulensi</button>';
			})
			->rawColumns(['action'])
			->make('true');
	}

	public function index()
	{
		$daftar_guru = User::where('role', 'guru')->get();
		$nama_semester = [
			'Semester Ganjil',
			'Semester Genap'
		];

		return view('guru.pertemuan', compact('daftar_guru', 'nama_semester'));
	}

	public function store(Request $request)
	{
		$validatedData = $request->validate([
			'schedule_id'     => 'required|numeric',
			'semester'      => 'required|string',
			'jam_masuk'      => 'required',
			'jam_keluar'     => 'required',
			'pertemuan'      => 'required|numeric',
			'tgl_pertemuan'  => 'required|date',
			'pokok_bahasan'  => 'required',
		]);

		$data_already_available = Attendance_report::where('schedule_id', $request->schedule_id)
			->where('pertemuan', $request->pertemuan)
			->get();

		if (count($data_already_available) != 0) {
			return abort(422, 'Notulensi pertemuan ini sudah ada.');
		}

		Attendance_report::create([
			'schedule_id'    => $request->schedule_id,
			'guru_pengganti' => $request->guru_pengganti,
			'semester' 			 => $request->semester,
			'jam_masuk'      => $request->jam_masuk,
			'jam_keluar'     => $request->jam_keluar,
			'pertemuan'      => $request->pertemuan,
			'tgl_pertemuan'  => $request->tgl_pertemuan,
			'pokok_bahasan'  => $request->pokok_bahasan,
			'tugas'          => $request->tugas,
		]);

		return json_encode([
			'status'  => 'success',
			'id'      => $request->schedule_id,
			'message' => 'Notulensi baru berhasil dibuat.'
		]);
	}

	public function show(Request $request)
	{
		if (isset($request->id)) {
			$return = [];
			$data = Attendance_report::find($request->id);

			if ($data) {
				$return['id']             = $data->id;
				$return['schedule_id']    = $data->schedule_id;
				$return['guru_pengganti'] = $data->guru_pengganti;
				$return['semester']			  = $data->semester;
				$return['jam_masuk']      = $data->jam_masuk;
				$return['jam_keluar']     = $data->jam_keluar;
				$return['pertemuan']      = $data->pertemuan;
				$return['tgl_pertemuan']  = $data->tgl_pertemuan;
				$return['pokok_bahasan']  = $data->pokok_bahasan;
				$return['tugas']          = $data->tugas;

				return response()->json([
					'status' => 'success',
					'data' => $return
				]);
			}
		}

		return abort(422, 'data yang anda cari tidak ditemukan');
	}

	public function update(Request $request)
	{
		$validatedData = $request->validate([
			'id'						 => 'required|numeric',
			'semester'       => 'required|string',
			'jam_masuk'      => 'required',
			'jam_keluar'     => 'required',
			'pertemuan'      => 'required|numeric',
			'tgl_pertemuan'  => 'required|date',
			'pokok_bahasan'  => 'required',
		]);

		Attendance_report::where('id', $request->id)
			->update([
				'schedule_id'    => $request->schedule_id,
				'guru_pengganti' => $request->guru_pengganti,
				'semester'			 => $request->semester,
				'jam_masuk'      => $request->jam_masuk,
				'jam_keluar'     => $request->jam_keluar,
				'pertemuan'      => $request->pertemuan,
				'tgl_pertemuan'  => $request->tgl_pertemuan,
				'pokok_bahasan'  => $request->pokok_bahasan,
				'tugas'          => $request->tugas,
			]);

		return json_encode([
			'status'  => 'success',
			'schedule_id' => $request->schedule_id,
			'message' => 'Notulensi berhasil diubah.'
		]);
	}
}
