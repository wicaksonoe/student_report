<?php

namespace App\Http\Controllers;

use App\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
  public function data()
	{
		# code...
	}

	public function index()
	{
		$user = Auth::user();
		$daftar_semester = Semester
		return view('guru.report', compact('user'));
	}

	public function store(Request $request)
	{
		# code...
	}

	public function show(Request $request)
	{
		# code...
	}

	public function update(Request $request)
	{
		# code...
	}
}
