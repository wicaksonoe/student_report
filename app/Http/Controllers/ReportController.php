<?php

namespace App\Http\Controllers;

use App\Report;
use App\Schedule;
use App\Semester;
use App\User;
use App\Student;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
	public function data_siswa(Request $request)
	{
		if (!isset($request->id)) {
			$request->id = 'all';
		}

		if ($request->id == 'all') {
			$user = Auth::user();
			$group_id = User::find($user->id)->kelas->id;
			$student_list = Student::where('group_id', $group_id)->get(['id', 'nis', 'name', 'photo']);

			// return $student_list;
			return DataTables::of($student_list)
				->addColumn('foto', function ($student) {
					return '<img src="storage/' . $student->photo . '" alt="foto ' . $student->name . '"	style="max-width: 100px">';
				})
				->addColumn('action', function ($student) {
					return '<button class="btn btn-sm btn-info" value="' . $student->id . '" onclick="show_cetak_raport(this.value)">Cetak</button>
									<button class="btn btn-sm btn-warning" value="' . $student->id . '" onclick="show_form(this.value)" style="margin-left: 2rem">Update</button>';
				})
				->rawColumns(['action', 'foto'])
				->make(true);
		} else {
			$data = [];
			$student = Student::find($request->id);

			$data['student_id'] = $student->id;
			$data['nama_student'] = $student->name;
			$data['group_id'] = $student->group_id;
			$data['kelas'] = $student->kelas->nama_kelas;

			return response()->json([
				'status' => 'success',
				'data' => $data
			]);
		}
	}

	public function data_nilai(Request $request)
	{
		if (isset($request->group_id) && isset($request->semester_id) && isset($request->student_id) && isset($request->semester)) {
			$data = [];

			$check_existing_data = Report::where([
				'semester_id' => $request->semester_id,
				'semester' => $request->semester,
				'student_id' => $request->student_id
			])->get();

			if (count($check_existing_data)) {
				foreach ($check_existing_data as $key => $value) {
					$data[$key]['teacher_id'] = $value->teacher_id;
					$data[$key]['nilai'] = $value->nilai;
					$data[$key]['nama_guru'] = $value->pelajaran->guru->name;
					$data[$key]['nama_matpel'] = $value->pelajaran->mata_pelajaran->nama_matpel;
				}

				return response()->json([
					'status' => 'success',
					'data' => $data,
				]);
			} else {
				$list_nilai = Schedule::where(['group_id' => $request->group_id, 'semester_id' => $request->semester_id])
					->groupBy(['group_id', 'semester_id', 'teacher_id'])
					->get(['group_id', 'semester_id', 'teacher_id']);

				foreach ($list_nilai as $key => $value) {
					$data[$key]['teacher_id'] = $value->teacher_id;
					$data[$key]['nilai'] = "0";
					$data[$key]['nama_guru'] = $value->pelajaran->guru->name;
					$data[$key]['nama_matpel'] = $value->pelajaran->mata_pelajaran->nama_matpel;
				}

				return response()->json([
					'status' => 'success',
					'data' => $data,
				]);
			}
		} else {
			return abort(404);
		}
	}

	public function index()
	{
		$user = Auth::user();
		$tahun_akademik = Semester::all();
		$semester = [
			'Mid-semester Ganjil',
			'Semester Ganjil',
			'Mid-semester Genap',
			'Semester Genap',
		];

		return view('guru.report', compact('user', 'semester', 'tahun_akademik'));
	}

	public function update(Request $request)
	{
		$data = [];
		$validate_data = $request->validate([
			'student_id'  => 'required|numeric',
			'semester_id' => 'required|numeric',
			'semester'    => 'required',
		]);

		foreach ($request->teacher_id as $key => $value) {
			if (!isset($request->nilai[$key])) {
				$nilai = 0;
			} else {
				$nilai = $request->nilai[$key];
			}

			$check_existing_data = Report::where([
				'student_id' => $request->student_id,
				'semester_id' => $request->semester_id,
				'semester' => $request->semester,
				'teacher_id'  => $value,
			])->get();

			if (count($check_existing_data)) {
				Report::where([
					'student_id'  => $request->student_id,
					'semester_id' => $request->semester_id,
					'semester'    => $request->semester,
					'group_id'    => $request->group_id,
					'teacher_id'  => $value,
				])
					->update([
						'nilai'       => $nilai,
					]);
			} else {
				Report::create([
					'student_id'  => $request->student_id,
					'semester_id' => $request->semester_id,
					'semester'    => $request->semester,
					'group_id'    => $request->group_id,
					'teacher_id'  => $value,
					'nilai'       => $nilai,
				]);
			}
		}

		return response()->json([
			'status' => 'success',
			'message' => "Nilai siswa berhasil di update.",
		]);
	}

	public function print(Request $request)
	{
		$data = [];
		$nilai = Report::where([
			'student_id'  => $request->cetak_student_id,
			'semester'    => $request->cetak_semester,
			'semester_id' => $request->cetak_semester_id,
		])->get();

		$data['nama_siswa']     = $nilai[0]->siswa->name;
		$data['nama_kelas']     = $nilai[0]->group_table->nama_kelas;
		$data['semester']       = $nilai[0]->semester;
		$data['tahun_akademik'] = $nilai[0]->semester_table->tahun_akademik;
		$data['wali_kelas']     = $nilai[0]->group_table->wali_kelas->name;

		foreach ($nilai as $key => $value) {
			$data['rekap_nilai'][$value->pelajaran->mata_pelajaran->kategori_matpel][] = [
				'nama_matpel' => $value->pelajaran->mata_pelajaran->nama_matpel,
				'kkm_matpel' => $value->pelajaran->mata_pelajaran->kkm,
				'guru_matpel' => $value->pelajaran->guru->name,
				'nilai'       => $value->nilai,
			];
		}

		$pdf = PDF::loadView('guru.cetak_report', compact('data'));
		
		$nama_file = 'raport_';
		$nama_file .= str_replace([' ', '-',], '_', strtolower($data['nama_siswa']));
		$nama_file .= '_';
		$nama_file .= str_replace([' ', '-'], '_', strtolower($data['semester']));
		$nama_file .= '_tahun_';
		$nama_file .= str_replace('/', '_', $data['tahun_akademik']);
		$nama_file .= '.pdf';

		return $pdf->setPaper('a4', 'portrait')->download($nama_file);

		return view('guru.cetak_report', compact('data'));
	}
}
