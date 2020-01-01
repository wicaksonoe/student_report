@extends('adminlte::page')

@section('content')
{{-- Meta CSRF --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Modal -->
<div class="modal fade" id="form_nilai" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title">Kelola Nilai Raport</h5>
			</div>
			<div class="modal-body">
				<input type="number" name="student_id" id="student_id"><br>
				<div class="form-group">
					<label for="nama_student">Nama Siswa</label>
					<input type="text" name="nama_student" id="nama_student" class="form-control" readonly>
				</div>
				<input type="number" name="group_id" id="group_id"><br>
				<div class="form-group">
					<label for="kelas">Kelas</label>
					<input type="text" name="kelas" id="kelas" class="form-control" readonly>
				</div>
				<div class="form-group">
					<label for="semester">Kelas</label>
					<select name="semester" id="semester" class="form-control">
						<option selected disabled>-- Pilih Semester --</option>
					</select>
				</div>
				<div id="nilai"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Simpan</button>
			</div>
		</div>
	</div>
</div>

{{-- Content --}}
<div class="container">
	<div class="row">
		<div class="col-md-10">
			@if (isset($user->kelas->nama_kelas))
			<div class="box box-solid box-default">
				<div class="box-header">
					<h1 class="box-title">Kelola nilai raport siswa kelas
						{{$user->kelas->nama_kelas}}
					</h1>
				</div>
				<div class="box-body">
					<button onclick="show_form()">kelola</button>
					<table id="table" class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th class="text-center">NIS</th>
								<th class="text-center">Foto</th>
								<th class="text-center">Nama</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			@else
			<h3>Anda belum menjadi wali kelas.</h3>
			@endif
		</div>
	</div>
</div>
@stop

@section('js')
<script>
	$(document).ready(() => {
		// createDataTable();
	})

	function show_form(id = 0) {
		$('#form_nilai').modal('show');
	}

</script>
@endsection