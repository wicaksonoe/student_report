@extends('adminlte::page')

@section('css')
<style>
	.border {
		border: 1px solid #ccc;
		border-radius: 10px;
		padding: 1.5rem;
	}
</style>
@endsection

@section('content')
{{-- Meta CSRF --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Modal -->
<div class="modal fade" id="modal_form_cetak" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Cetak Raport Siswa</h4>
			</div>
			<div class="modal-body">
				<form action="{{ route('nilai.print') }}" method="post" id="form_cetak">
					{{ csrf_field() }}

					<div class="form-group">
						<input type="number" name="cetak_student_id" id="cetak_student_id" readonly hidden>
						<h5><strong>Cetak Raport Atas Nama: </strong><i id="cetak_nama_siswa">asdasdad</i></h5>
					</div>
					<div class="form-group">
						<label for="cetak_semester">Semester</label>
						<select name="cetak_semester" id="cetak_semester" class="form-control">
							<option selected disabled>-- Pilih Semester --</option>
							@foreach ($semester as $item)
							<option value="{{ $item }}">{{ $item }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="cetak_semester_id">Tahun Akademik</label>
						<select name="cetak_semester_id" id="cetak_semester_id" class="form-control">
							<option selected disabled>-- Pilih Tahun Akademik --</option>
							@foreach ($tahun_akademik as $item)
							<option value="{{ $item->id }}">{{ $item->tahun_akademik }}</option>
							@endforeach
						</select>
					</div>
					<button type="submit" class="btn btn-primary">Cetak</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="form_nilai" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Kelola Nilai Raport</h4>
			</div>
			<div class="modal-body">
				<form id="form_input_nilai">
					<input type="number" name="student_id" id="student_id" readonly hidden>
					<div class="form-group">
						<label for="nama_student">Nama Siswa</label>
						<input type="text" name="nama_student" id="nama_student" class="form-control" readonly>
					</div>
					<input type="number" name="group_id" id="group_id" readonly hidden>
					<div class="form-group">
						<label for="kelas">Kelas</label>
						<input type="text" name="kelas" id="kelas" class="form-control" readonly>
					</div>
					<div class="form-group">
						<label for="semester">Semester</label>
						<select name="semester" id="semester" class="form-control" onchange="validate_select()">
							<option selected disabled>-- Pilih Semester --</option>
							@foreach ($semester as $item)
							<option value="{{ $item }}">{{ $item }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="semester_id">Tahun Akademik</label>
						<select name="semester_id" id="semester_id" class="form-control" onchange="validate_select()">
							<option selected disabled>-- Pilih Tahun Akademik --</option>
							@foreach ($tahun_akademik as $item)
							<option value="{{ $item->id }}">{{ $item->tahun_akademik }}</option>
							@endforeach
						</select>
					</div>
					<div id="nilai">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="kirim_data_nilai()">Simpan</button>
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
		let elem = '#table';
		let url = '{{ url("nilai/data/siswa") }}';
		let columns = [
			{width: '10%', data: 'nis', name: 'nis'},
			{width: '20%', data: 'foto', name: 'foto'},
			{width: '50%', data: 'name', name: 'name'},
			{width: '20%', data: 'action', name: 'action', searchable: false, orderable: false},
		];
		load_data(elem, url, columns);
	})

	$('#form_nilai').on('hide.bs.modal', (e) => { 
		document.getElementById('form_input_nilai').reset()
		$('#nilai').children().remove();
	})

	$('#modal_form_cetak').on('hide.bs.modal', (e) => { 
		document.getElementById('form_cetak').reset();
	})

	function show_cetak_raport(student_id) {
		$('#cetak_nama_siswa').html('');
		$.getJSON(
			'{{ url("nilai/data/siswa") }}',
			{id: student_id},
			(res) => {
				$('#cetak_student_id').val(student_id);
				$('#cetak_nama_siswa').html(res.data.nama_student);
				$('#modal_form_cetak').modal('show');
			}
		)
	}

	function show_form(student_id = 0) {
		$('#form_nilai').modal('show');
		$.getJSON(
			'{{ url("nilai/data/siswa") }}',
			{id: student_id},
			(res) => {
				$.each(res.data, (key, val) => {
					$('#' + key).val(val);
				});
			}
		)
	}

	function validate_select() {
		if ($('#semester').val() && $('#semester_id').val()) {
			get_daftar_matpel();
		}
	}

	function get_daftar_matpel() {
		$.getJSON(
			'{{ url("nilai/data/raport") }}',
			{
				group_id: $('#group_id').val(),
				student_id: $('#student_id').val(),
				semester: $('#semester').val(),
				semester_id: $('#semester_id').val(),
			},
			(res) => {
				$('#nilai').children().remove();
				$.each(res.data, (key, val) => {
					$('#nilai').append('<div class="form-group border"> \
									<h5 style="margin: 0"><strong>Mata Pelajaran: </strong>' + val.nama_matpel + '</h5> \
									<h5 style="margin-top: 0"><strong>Guru Pengajar: </strong>' + val.nama_guru + '</h5> \
									<label>Nilai</label> \
									<input type="number" name="nilai[]" class="form-control" value="' + val.nilai + '" required> \
									<input type="number" name="teacher_id[]" value="' + val.teacher_id + '" readonly hidden> \
								</div>')
				});
			}
		)
	}

	function kirim_data_nilai() {
		let data_form = $('form').serializeArray();
		// console.log(data_form);
		$.ajax({
			url    : "{{ route('nilai.update') }}",
			method : 'post',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data       : data_form,
			dataType   : 'json',
			success    : (res) => {
				Swal.fire(
					res.status,
					res.message,
					'success'
				);
				$('#form_nilai').modal('hide');
				document.getElementById('form_input_nilai').reset();
			},
			error      : (res) => {
				let err = res.responseJSON;

				Swal.fire(
					'Error',
					err.message,
					'error'
				);
			}
		});
	}

	function load_data(elem, url, columns, order = [0, 'asc']) {
		$(elem).DataTable().clear().destroy();
		$(elem).DataTable({
			processing: false,
			serverSide: true,
			ajax      : url,
			columns   : columns,
			order: order,
			responsive: true,
		});
	}

</script>
@endsection