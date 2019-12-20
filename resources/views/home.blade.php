@extends('adminlte::page')

@section('content_header')
<h1>Selamat Datang {{ Auth::user()->name }}</h1>
@stop

@section('content')
	@can('guru', Auth::user())

	@elsecan ('pengurus', Auth::user())
		Halo Pengurus
	@endcan
@stop