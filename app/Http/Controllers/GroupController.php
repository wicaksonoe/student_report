<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GroupController extends Controller
{
	public function data()
	{
		$data_kelas = Group::all();

		return DataTables::of($data_kelas)
			->addColumn('wali_kelas', function($data_kelas) {
				if(isset($data_kelas->user_id)) {
					return $data_kelas->wali_kelas->name;
				} else {
					return '<i>--Tidak ada wali kelas--</i>';
				}
			})
			->addColumn('jumlah_siswa', function($data_kelas) {
				return $data_kelas->jumlah_siswa->count();
			})
			->addColumn('action', function($data_kelas) {
				return '
					<button class="btn btn-sm btn-warning" style="margin: 1rem" value="'. route('kelas.edit', $data_kelas->id) . '" onclick="edit(this)">Edit</button>
					<button class="btn btn-sm btn-danger"  style="margin: 1rem" value="'. route('kelas.destroy', $data_kelas->id) . '" onclick="destroy(this)">Delete</button>
				';
			})
			->rawColumns(['wali_kelas', 'jumlah_siswa', 'action'])
			->make(true);
	}

  public function index()
	{
		$wali_kelas = User::where('role', 'guru')->get();
		return view('pengurus.kelas', compact('wali_kelas'));
	}

	public function store(Request $request)
	{
		$validateData = $request->validate([
			'user_id'    => 'nullable|numeric|unique:groups,user_id',
			'nama_kelas' => 'required|unique:groups,nama_kelas'
		]);

		$nama_kelas = strtoupper($request->nama_kelas);

		Group::create([
			'user_id'    => $request->user_id,
			'nama_kelas' => $nama_kelas
		]);

		return json_encode([
			'status' => 'success',
			'message' => 'Kelas baru berhasil dibuat',
		]);
	}

	public function edit(Group $id)
	{
		return json_encode($id);
	}

	public function update(Request $request)
	{
		$validateData = $request->validate([
			'user_id'    => 'nullable|numeric',
			'nama_kelas' => 'required'
		]);

		$nama_kelas = strtoupper($request->nama_kelas);

		Group::where('id', $request->id)
			->update([
				'user_id'    => $request->user_id,
				'nama_kelas' => $nama_kelas
			]);

		return json_encode([
			'status' => 'success',
			'message' => 'Kelas berhasil diubah',
		]);
	}

	public function destroy(Group $id)
	{
		$id->delete();

		return json_encode([
			'status' => 'success',
			'message' => 'Kelas berhasil dihapus',
		]);
	}
}
