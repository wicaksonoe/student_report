<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function editProfile()
    {
        $daftar_kelas = Group::all();
        $user_data = User::find(Auth::id());

        return view('profile.index', compact('daftar_kelas', 'user_data'));
    }

    public function updateProfile(Request $request)
    {
        $photo = time().'_'.$request->file('photo')->getClientOriginalName();

        $photoDir = 'images/photo/guru';

        $request->file('photo')->move($photoDir, $photo);

        User::where('id', Auth::id())
            ->update([
                'group_id' => $request->group_id,
                'name' => $request->name,
                'photo' => $photo,
                'email' => $request->email,
            ]);

        return redirect()->route('home');
    }
}
