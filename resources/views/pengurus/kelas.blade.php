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
				<form id="modalForm">
					<input type="text" name="id" id="id" readonly hidden>
					<div class="form-group">
						<label for="kategori_matpel">Wali Kelas</label>
						<select name="user_id" id="user_id" class="form-control">
							<option selected disabled>-- Pilih Wali Kelas --</option>
							<option value="">--Belum ada wali--</option>
							@foreach ($wali_kelas as $wali)
								<option value="{{ $wali->id }}">{{ $wali->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="nama_kelas">Nama Kelas</label>
						<input type="text" name="nama_kelas" id="nama_kelas" class="form-control">
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
			<div class="box box-solid box-default">
				<div class="box-header">
					<h1 class="box-title">Kelola Kelas</h1>
				</div>
				<div class="box-body">
					<button class="btn btn-primary" onclick="tambah()" style="margin-bottom: 1rem"><span
							class="fas fa-fw fa-plus-circle"></span> Tambah Kelas</button>
					<table id="table" class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Nama Kelas</th>
								<th>Wali Kelas</th>
								<th>Jumlah Siswa</th>
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
		$('#update').attr('disabled', true);
		$('#create').attr('disabled', false);

		$('#modalTitle').html("Tambah Mata Pelajaran");
		$('#modal').modal('show');
	}

	function create() {
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();

		$.ajax({
			url    : "{{ route('kelas.store') }}",
			method : 'post',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data       : {
				'user_id'   : $('#user_id').val(),
				'nama_kelas': $('#nama_kelas').val(),
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
				$.each(res, (key, val) => {
					$('#' + key).val(val);
				})
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
			url    : "{{ route('kelas.update') }}",
			method : 'post',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data       : {
				'id'        : $('#id').val(),
				'user_id'   : $('#user_id').val(),
				'nama_kelas': $('#nama_kelas').val(),
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

	function loadData() {
		$('#table').DataTable().clear().destroy();
		$('#table').DataTable({
			processing: false,
			serverSide: true,
			ajax      : '{{ url('kelas/data') }}',
			columns   : [
				{width: '20%', data: 'nama_kelas', name: 'nama_kelas'},
				{width: '40%', data: 'wali_kelas', name: 'wali_kelas'},
				{width: '10%', data: 'jumlah_siswa', name: 'jumlah_siswa', searchable: false},
				{width: '30%', data: 'action', name: 'action', orderable: false, searchable: false},
			],
			order: [[0, 'asc']],
			responsive: true,
		});
	}
</script>
@endsection