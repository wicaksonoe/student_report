<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $student_datas = Student::all(['id', 'nis', 'photo', 'name', 'tahun_masuk']);
        return view('student.index', compact('student_datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('student.create');
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
            'nis'          => 'required|numeric',
            'name'         => 'required|unique:students,name',
            'tahun_masuk'  => 'required|numeric',
            'tempat_lahir' => 'required',
            'tgl_lahir'    => 'required|date',
            'nama_ayah'    => 'required',
            'nama_ibu'     => 'required',
            'no_hp_ortu'   => 'required|numeric',
            'alamat'       => 'required',
            'no_hp'        => 'required|numeric',
            'photo'        => 'required|file|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        $photo_name = time().'_'.$request->file('photo')->getClientOriginalName();
        $photo = $request->file('photo')->storeAs('photo/siswa', $photo_name, 'public');

        Student::create([
            'nis'          => $request->nis,
            'name'         => $request->name,
            'tahun_masuk'  => $request->tahun_masuk,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir'    => $request->tgl_lahir,
            'nama_ayah'    => $request->nama_ayah,
            'nama_ibu'     => $request->nama_ibu,
            'no_hp_ortu'   => $request->no_hp_ortu,
            'alamat'       => $request->alamat,
            'no_hp'        => $request->no_hp,
            'photo'        => $photo,
        ]);

        if (isset($request->input_again)) {
            return redirect()->back();
        } else {
            return redirect()->route('student.index');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
