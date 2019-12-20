<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
	public function editProfile()
	{
		$daftar_kelas = Group::all();
		$user_data = User::find(Auth::id());

		return view('profile.index', compact('daftar_kelas', 'user_data'));
	}

	public function updateProfile(Request $request)
	{
		$user = User::find(Auth::id());

		if (isset($request->password) && Hash::check($request->password, $user->password)) {
			$validatedData = $request->validate([
				'name' => 'required',
				'email' => 'required|email',
				'photo' => 'required|file|image|mimes:jpg,jpeg,png|max:1024',
			]);

			$photo_name = time() . '_' . $request->file('photo')->getClientOriginalName();
			$photo = $request->file('photo')->storeAs('photo/guru', $photo_name, 'public');
			$old_photo = '/public/' . $user->photo;

			if (Storage::exists($old_photo)) {
				Storage::delete($old_photo);
			}

			User::where('id', Auth::id())
				->update([
					'name' => $request->name,
					'photo' => $photo,
					'email' => $request->email,
				]);

			return redirect()->route('home');
		} else {
			return redirect()->back()->with('error', 'Password konfirmasi salah.');
		}
	}

	public function resetPassword()
	{
		return view('profile.password');
	}

	public function updatePassword(Request $request)
	{
		$user = User::find(Auth::id());

		if (isset($request->password_old) && Hash::check($request->password_old, $user->password)) {
			$validationRequest = $request->validate([
				'password_old' => 'required',
				'password'     => 'required|confirmed'
			]);

			User::where('id', Auth::id())
				->update([
					'password' => Hash::make($request->password)
				]);

			return redirect('/');
		} else {
			return redirect()->back()->with('error', 'Password lama salah.');
		}
	}
}
