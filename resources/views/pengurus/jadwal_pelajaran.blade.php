@extends('adminlte::page')

@section('content')
{{-- Meta CSRF --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Modal --}}
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title" id="modalTitle"></h3>
			</div>
			<div class="modal-body">
				<input type="text" name="id" id="id" hidden readonly>
				<form id="modalForm">
					<div class="form-group">
						<label for="group_id">Kelas</label>
						<select type="text" name="group_id" id="group_id" class="form-control" onchange="$('#semester_id').attr('disabled', false)">
							<option disabled selected>-- Pilih Kelas --</option>
							@foreach ($kelas as $item)
							<option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="semester_id">Semester</label>
						<select type="text" name="semester_id" id="semester_id" class="form-control" onchange="$('#hari').attr('disabled', false)" disabled="true">
							<option disabled selected>-- Pilih Semester --</option>
							@foreach ($semester as $item)
							<option value="{{ $item->id }}">{{ $item->keterangan.' - '.$item->tahun_akademik }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="hari">Hari</label>
						<select type="text" name="hari" id="hari" class="form-control" onchange="$('#lesson_hour_id').attr('disabled', false)" disabled="true">
							<option disabled selected>-- Pilih Hari --</option>
							<option value="1">Senin</option>
							<option value="2">Selasa</option>
							<option value="3">Rabu</option>
							<option value="4">Kamis</option>
							<option value="5">Jumat</option>
							<option value="6">Sabtu</option>
						</select>
					</div>
					<div class="form-group">
						<label for="lesson_hour_id">Waktu</label>
						<select type="text" name="lesson_hour_id" id="lesson_hour_id" class="form-control" onchange="$('#course_id').attr('disabled', false)" disabled="true">
							<option disabled selected>-- Pilih Waktu --</option>
							@foreach ($waktu as $item)
							<option value="{{ $item->id }}">{{ '('.$item->sesi.') '.$item->waktu }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="course_id">Mata Pelajaran</label>
						<select type="text" name="course_id" id="course_id" class="form-control" onchange="get_data_guru()" disabled="true">
							<option disabled selected>-- Pilih Mata Pelajaran --</option>
							@foreach ($mata_pelajaran as $item)
							<option value="{{ $item->id }}">{{ $item->nama_matpel }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="teacher_id">Guru Pengajar</label>
						<select type="text" name="teacher_id" id="teacher_id" class="form-control" disabled="true">
							<option disabled selected>-- Pilih Guru Pengajar --</option>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" id="update" class="btn btn-warning" onclick="update()">Update</button>
				<button type="button" id="create" class="btn btn-primary" onclick="create()">Create</button>
			</div>
		</div>
	</div>
</div>

{{-- Content --}}
<div class="container">
	<div class="row">
		<div class="col-md-10">
			<a href="{{ route('jadwal.jam.index') }}" class="btn btn-secondary" style="margin-bottom: 2rem;">Kelola Jam Pelajaran</a>
			<a href="{{ route('jadwal.kelas.index') }}" class="btn btn-primary" style="margin-bottom: 2rem; margin-left: 2rem;">Kelola Jadwal Pelajaran Kelas</a>
			<div class="box box-solid box-default">
				<div class="box-header">
					<h1 class="box-title">Kelola Jadwal Pelajaran</h1>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-3">
							<button class="btn btn-primary" onclick="tambah()" style="margin-bottom: 1rem"><span class="fas fa-fw fa-plus-circle"></span> Tambah Jadwal Pelajaran</button>
						</div>
						<div class="col-md-5">
							<div class="form-group form-inline">
								<label for="filter">Filter kelas: </label>
								<select id="filter" type="text" class="form-control" onchange="loadData(this)">
									<option value="all">Semua kelas</option>
									@foreach ($kelas as $item)
									<option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<table id="table" class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Kelas</th>
								<th>Semester</th>
								<th>Hari</th>
								<th>Waktu</th>
								<th>Mata Pelajaran</th>
								<th>Guru</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@stop

@section('js')
<script>
	$(document).ready(() => {
		loadData();
	});

	function tambah() {
		document.getElementById('modalForm').reset();
		$('.nama_guru').remove();
		$('#group_id').attr('disabled', false);
		$('#semester_id').attr('disabled', true)
		$('#hari').attr('disabled', true)
		$('#lesson_hour_id').attr('disabled', true)
		$('#course_id').attr('disabled', true)
		$('#teacher_id').attr('disabled', true)

		$('#update').attr('disabled', true);
		$('#create').attr('disabled', false);

		$('#modalTitle').html("Tambah Mata Pelajaran");
		$('#modal').modal('show');
	}

	function create() {
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();

		$.ajax({
			url    : "{{ route('jadwal.kelas.store') }}",
			method : 'post',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data       : {
				'group_id'      : $('#group_id').val(),
				'semester_id'   : $('#semester_id').val(),
				'lesson_hour_id': $('#lesson_hour_id').val(),
				'course_id'     : $('#course_id').val(),
				'teacher_id'    : $('#teacher_id').val(),
				'hari'          : $('#hari').val(),
			},
			dataType   : 'json',
			success    : (res) => {
				loadData();
				Swal.fire(
					res.status,
					res.message,
					'success'
				);
				$('#modal').modal('hide');
				document.getElementById('modalForm').reset();
			},
			error      : (res) => {
				let err = res.responseJSON;

				$.each(err.errors, (el, val) => {
					$('#' + el).parent().addClass('has-error');
					$('#' + el).parent().append("<span class='help-block error'>" + val + "</span>");
				});

				Swal.fire(
					'Error',
					err.message,
					'error'
				);
			}
		});
	}

	function edit(el) {
		$.get(
			el.value,
			(res) => {
				$('#group_id').attr('disabled', true);
				$('#semester_id').attr('disabled', false);
				$('#hari').attr('disabled', false);
				$('#lesson_hour_id').attr('disabled', false);
				$('#course_id').attr('disabled', false);
				$('#teacher_id').attr('disabled', false);

				$('.nama_guru').remove();
				$.each(res.daftar_guru, (key, val) => {
					$('#teacher_id').append('<option value="' + val.id + '" class="nama_guru">' + val.nama_guru + '</option>');
				});

				$.each(res, (key, val) => {
					$('#' + key).val(val);
				})
				$('#modalTitle').html("Edit Mata Pelajaran");
				$('#create').attr('disabled', true);
				$('#update').attr('disabled', false);
				$('#modal').modal('show');
			},
			'json'
		);
	}

	function update() {
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();

		$.ajax({
			url    : "{{ route('jadwal.kelas.update') }}",
			method : 'post',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data       : {
				'id'            : $('#id').val(),
				'group_id'      : $('#group_id').val(),
				'semester_id'   : $('#semester_id').val(),
				'lesson_hour_id': $('#lesson_hour_id').val(),
				'course_id'     : $('#course_id').val(),
				'teacher_id'    : $('#teacher_id').val(),
				'hari'          : $('#hari').val(),
			},
			dataType   : 'json',
			success    : (res) => {
				loadData();
				Swal.fire(
					res.status,
					res.message,
					'success'
				);
				$('#modal').modal('hide');
				$('#filter').val('all');
				document.getElementById('modalForm').reset();
			},
			error      : (res) => {
				let err = res.responseJSON;

				$.each(err.errors, (el, val) => {
					$('#' + el).parent().addClass('has-error');
					$('#' + el).parent().append("<span class='help-block error'>" + val + "</span>");
				});

				Swal.fire(
					'Error',
					err.message,
					'error'
				);
			}
		});
	}

	function destroy(el) {
		Swal.fire({
			title            : "Apakah anda yakin ingin menghapus data?",
			text             : "Data yang sudah dihapus tidak dapat dikembalikan.",
			type             : 'warning',
			showCancelButton : true,
			confirmButtonText: "Yakin",
			cancelButtonText : "Tidak",
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url    : el.value,
					method : 'delete',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					dataType   : 'json',
					success    : (res) => {
						loadData();
						Swal.fire(
							res.status,
							res.message,
							'success'
						);
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
		});
	}

	function loadData(el = 'all') {
		let param;

		if (el == 'all') {
			$('#filter').val('all');
			param = {
				value: 'all'
			}
		} else {
			param = el
		}

		$('#table').DataTable().clear().destroy();
		$('#table').DataTable({
			processing: false,
			serverSide: true,
			ajax      : '{{ url('jadwal/kelas/data?group_id=') }}' + param.value ,
			columns   : [
				{width: '10%', data: 'kelas', name: 'kelas'},
				{width: '20%', data: 'semester', name: 'semester'},
				{width: '10%', data: 'hari', name: 'hari'},
				{width: '10%', data: 'waktu', name: 'waktu'},
				{width: '15%', data: 'mata_pelajaran', name: 'mata_pelajaran'},
				{width: '15%', data: 'guru', name: 'guru'},
				{width: '20%', data: 'action', name: 'action', orderable: false, searchable: false},
			],
			order: [[0, 'asc']],
			responsive: true,
		});
	}

	function get_data_guru() {
		$('#teacher_id').attr('disabled', false)

		let id = $('#course_id').val();
		
		$.ajax({
			url    : '{{ url('jadwal/kelas/data/matpel') }}',
			method : 'get',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data       : {
				'id': $('#course_id').val(),
			},
			dataType   : 'json',
			success    : (res) => {				
				$('.nama_guru').remove();
				$.each(res.data, (key, val) => {
					$('#teacher_id').append('<option value="' + val.id + '" class="nama_guru">' + val.nama_guru + '</option>');
				});
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
</script>
@endsection