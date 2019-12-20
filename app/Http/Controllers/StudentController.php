<?php

namespace App\Http\Controllers;

use App\Group;
use App\Student;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	private $input = [
		'nis' => [
			'type' => 'text',
			'label' => 'NIS',
			'error' => 'NIS harus diisi.'
		],
		'group_id' => [
			'type' => 'select',
			'label' => 'Kelas',
			'error' => 'Kelas harus diisi.'
		],
		'name' => [
			'type' => 'text',
			'label' => 'Nama Lengkap',
			'error' => 'Nama lengkap harus diisi.'
		],
		'jenis_kelamin' => [
			'type' => 'radio',
			'label' => 'Jenis Kelamin'
		],
		'tahun_masuk' => [
			'type' => 'number',
			'label' => 'Tahun Masuk',
			'error' => 'Tahun masuk harus diisi.'
		],
		'tempat_lahir' => [
			'type' => 'text',
			'label' => 'Tempat Lahir',
			'error' => 'Tempat lahir harus diisi.'
		],
		'tgl_lahir' => [
			'type' => 'date',
			'label' => 'Tanggal Lahir',
			'error' => 'Tanggal lahir harus diisi.'
		],
		'nama_ayah' => [
			'type' => 'text',
			'label' => 'Nama Ayah',
			'error' => 'Nama ayah harus diisi.'
		],
		'nama_ibu' => [
			'type' => 'text',
			'label' => 'Nama Ibu',
			'error' => 'Nama ibu harus diisi.'
		],
		'no_hp_ortu' => [
			'type' => 'number',
			'label' => 'Nomor Telepon Orang Tua',
			'error' => 'nomor telepon orang tua harus diisi.'
		],
		'alamat' => [
			'type' => 'textarea',
			'label' => 'Alamat Tempat Tinggal',
			'error' => 'Alamat tempat tinggal harus diisi.'
		],
		'no_hp' => [
			'type' => 'number',
			'label' => 'Nomor Telepon Siswa',
			'error' => 'Nomor telepon siswa harus diisi'
		],
		'photo' => [
			'type' => 'file',
			'label' => 'Foto Profil',
			'error' => 'Foto profil harus diisi.'
		],
	];

	public function index()
	{
		$input = $this->input;
		$user = Auth::user();
		$kelas = Group::all();

		return view('student.index', compact(['input', 'user', 'kelas']));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$validatedData = $request->validate([
			'nis'           => 'required|numeric',
			'name'          => 'required|unique:students,name',
			'jenis_kelamin' => 'required',
			'tahun_masuk'   => 'required|numeric',
			'tempat_lahir'  => 'required',
			'tgl_lahir'     => 'required|date',
			'nama_ayah'     => 'required',
			'nama_ibu'      => 'required',
			'no_hp_ortu'    => 'required|numeric',
			'alamat'        => 'required',
			'no_hp'         => 'required|numeric',
			'photo'         => 'bail|required|file|image|mimes:jpg,jpeg,png|max:1024',
		]);

		$photo_name = time() . '_' . $request->file('photo')->getClientOriginalName();
		$photo = $request->file('photo')->storeAs('photo/siswa', $photo_name, 'public');

		Student::create([
			'nis'           => $request->nis,
			'group_id'      => $request->group_id,
			'name'          => $request->name,
			'jenis_kelamin' => $request->jenis_kelamin,
			'tahun_masuk'   => $request->tahun_masuk,
			'tempat_lahir'  => $request->tempat_lahir,
			'tgl_lahir'     => $request->tgl_lahir,
			'nama_ayah'     => $request->nama_ayah,
			'nama_ibu'      => $request->nama_ibu,
			'no_hp_ortu'    => $request->no_hp_ortu,
			'alamat'        => $request->alamat,
			'no_hp'         => $request->no_hp,
			'photo'         => $photo,
		]);

		return json_encode([
			'status' => 'success',
			'message' => 'Data Baru Berhasil Dimasukan',
		]);
	}

	public function data()
	{
		$user = Auth::user();

		$student_datas = Student::where('group_id', $user->kelas->id)
												->get(['id', 'nis', 'photo', 'name', 'tahun_masuk']);

		return DataTables::of($student_datas)
			->addColumn('foto', function ($student_datas) {
				return '
					<img src="storage/' . $student_datas->photo . '" alt="foto ' . $student_datas->name . '"	style="max-width: 100px">
				';
			})
			->addColumn('action', function ($student_datas) {
				return '
					<button onclick="detailDataSiswa(this)" class="btn btn-sm btn-primary detail" style="margin: 1rem" 	value="' . route("student.detail", $student_datas->id) . '">Detail</button>
					<button onclick="editDataSiswa(this)" class="btn btn-sm btn-warning edit" style="margin: 1rem" 			value="' . route("student.detail", $student_datas->id) . '">Edit</button>
					<button onclick="hapusDataSiswa(this)" class="btn btn-sm btn-danger delete" style="margin: 1rem" 		value="' . route("student.destroy", $student_datas->id) . '">Hapus</button>
				';
			})
			->rawColumns(['foto', 'action'])
			->make(true);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Student  $student
	 * @return \Illuminate\Http\Response
	 */
	public function show(Student $student)
	{
		$data = collect($student);
		$data->put('kelas', $student->kelas->nama_kelas);
		return $data;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Student  $student
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		$existingStudent = Student::find($request->id);
		Storage::delete('public/'.$existingStudent->photo);
		
		$validatedDataUpdate = $request->validate([
			'nis'           => 'required|numeric',
			'name'          => 'required',
			'jenis_kelamin' => 'required',
			'tahun_masuk'   => 'required|numeric',
			'tempat_lahir'  => 'required',
			'tgl_lahir'     => 'required|date',
			'nama_ayah'     => 'required',
			'nama_ibu'      => 'required',
			'no_hp_ortu'    => 'required|numeric',
			'alamat'        => 'required',
			'no_hp'         => 'required|numeric',
			'photo'         => 'bail|required|file|image|mimes:jpg,jpeg,png|max:1024',
		]);

		$photo_name = time() . '_' . $request->file('photo')->getClientOriginalName();
		$photo = $request->file('photo')->storeAs('photo/siswa', $photo_name, 'public');

		Student::where('id', $request->id)
			->update([
				'nis'           => $request->nis,
				'group_id'      => $request->group_id,
				'name'          => $request->name,
				'jenis_kelamin' => $request->jenis_kelamin,
				'tahun_masuk'   => $request->tahun_masuk,
				'tempat_lahir'  => $request->tempat_lahir,
				'tgl_lahir'     => $request->tgl_lahir,
				'nama_ayah'     => $request->nama_ayah,
				'nama_ibu'      => $request->nama_ibu,
				'no_hp_ortu'    => $request->no_hp_ortu,
				'alamat'        => $request->alamat,
				'no_hp'         => $request->no_hp,
				'photo'         => $photo,
			]);

		return json_encode([
			'status' => 'success',
			'message' => 'Data Baru Berhasil Dimasukan',
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Student  $student
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Student $student)
	{
		// unlink photo
		Storage::delete('public/'.$student->photo);

		$student->delete();
		return json_encode('success');
	}
}
