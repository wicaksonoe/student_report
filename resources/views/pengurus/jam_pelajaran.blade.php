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
						<label for="sesi">Sesi</label>
						<input type="text" name="sesi" id="sesi" class="form-control" placeholder="Contoh: pagi, siang, sore">
					</div>
					<div class="form-group">
						<label for="waktu_awal">Waktu Mulai</label>
						<input type="text" name="waktu_awal" id="waktu_awal" class="form-control" placeholder="Contoh: 14.00">
						<span class="help-block">*Gunakan format 24 jam.</span>
					</div>
					<div class="form-group">
						<label for="waktu_akhir">Waktu Berakhir</label>
						<input type="text" name="waktu_akhir" id="waktu_akhir" class="form-control" placeholder="Gunakan format 24 jam. Contoh: 14.00">
						<span class="help-block">*Gunakan format 24 jam.</span>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" id="create" class="btn btn-primary" onclick="create()">Create</button>
			</div>
		</div>
	</div>
</div>

{{-- Content --}}
<div class="container">
	<div class="row">
		<div class="col-md-10">
			<a href="{{ route('jadwal.jam.index') }}" class="btn btn-primary" style="margin-bottom: 2rem;">Kelola Jam Pelajaran</a>
			<a href="{{ route('jadwal.kelas.index') }}" class="btn btn-secondary" style="margin-bottom: 2rem; margin-left: 2rem;">Kelola Jadwal Pelajaran Kelas</a>
			<div class="box box-solid box-default">
				<div class="box-header">
					<h1 class="box-title">Kelola Jam Pelajaran</h1>
				</div>
				<div class="box-body">
					<button class="btn btn-primary" onclick="tambah()" style="margin-bottom: 1rem"><span
							class="fas fa-fw fa-plus-circle"></span> Tambah Jam Pelajaran</button>
					<table id="table" class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Sesi</th>
								<th>Waktu</th>
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
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();
		document.getElementById('modalForm').reset();

		$('#modalTitle').html("Tambah Jadwal Pelajaran");
		$('#modal').modal('show');
	}

	function create() {
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();

		$.ajax({
			url    : "{{ route('jadwal.jam.store') }}",
			method : 'post',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data       : {
				'sesi'       : $('#sesi').val(),
				'waktu_awal' : $('#waktu_awal').val(),
				'waktu_akhir': $('#waktu_akhir').val(),
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
			ajax      : '{{ url('jadwal/jam/data') }}',
			columns   : [
				{width: '40%', data: 'sesi', name: 'sesi'},
				{width: '40%', data: 'waktu', name: 'waktu'},
				{width: '20%', data: 'action', name: 'action', orderable: false, searchable: false},
			],
			order: [[0, 'asc']],
			responsive: true,
		});
	}
</script>
@endsection