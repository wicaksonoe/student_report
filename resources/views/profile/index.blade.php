@extends('adminlte::page')

{{-- @section('content_header')
    <h1>Profile</h1>
@stop --}}

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8">
			<div class="box box-solid box-default">
				<div class="box-header">
					<h1 class="box-title">Profile</h1>
				</div>
				<div class="box-body">
					@if ($errors->any() || session('error'))
					<div class="alert alert-danger" role="alert">
						<ul>
							@if (session('error'))
							<li>{{ session('error') }}</li>
							@endif
							@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
					@endif
					<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-group">
							<label for="name">Nama Lengkap</label>
							<input type="text" name="name" id="name" class="form-control" value="{{ $user_data->name }}">
						</div>
						@can('guru', Auth::user())
							<div class="form-group">
								<label for="wali">Wali Kelas</label>
								<input type="text" name="wali" id="wali" class="form-control" value="{{ $user_data->kelas->nama_kelas }}" readonly>
							</div>
						@endcan
						<div class="form-group">
							<label for="email">E-Mail</label>
							<input type="email" name="email" id="email" class="form-control" value="{{ $user_data->email }}">
						</div>
						<div class="form-group">
							<label for="photo">Foto Profil</label>
							<input type="file" name="photo" id="photo" class="form-control">
						</div>
						<br><br>
						<div class="form-group">
							<label for="password">Masukan Password Anda Untuk Konfirmasi</label>
							<input type="password" name="password" id="password" class="form-control">
						</div>
						<button type="submit" class="btn btn-primary">Update Profile</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@stop