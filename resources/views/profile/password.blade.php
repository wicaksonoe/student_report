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
					<h1 class="box-title">Change Password</h1>
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
					<form action="{{ route('profile.reset') }}" method="POST" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-group">
							<label for="password_old">Masukan Password Lama Anda</label>
							<input type="password" name="password_old" id="password_old" class="form-control">
						</div>
						<div class="form-group">
							<label for="password">Masukan Password Baru</label>
							<input type="password" name="password" id="password" class="form-control">
						</div>
						<div class="form-group">
							<label for="password_confirmation">Ulangi Masukan Password Baru</label>
							<input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
						</div>
						<button type="submit" class="btn btn-primary">Ubah Password</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@stop