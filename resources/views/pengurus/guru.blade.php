@extends('adminlte::page')

@section('content')
{{-- Meta CSRF --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Modal Detail --}}
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title" id="modalDetailTitle"></h3>
			</div>
			<div class="modal-body">
				<button class="btn btn-primary" onclick="tambah()" style="margin-bottom: 0.5rem"><span
							class="fas fa-fw fa-plus-circle"></span> Tambah</button>
				<table id="tableModal" class="table table-bordered table-hover table-striped" style="width:100%">
					<thead>
						<tr>
							<th id="colModal"></th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>

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
						<label for="course_id">Nama Mata Pelajaran</label>
						<select name="course_id" id="course_id" class="form-control">
							<option selected disabled>-- Pilih Mata Pelajaran --</option>
							@foreach ($daftar_mata_pelajaran as $mata_pelajaran)
									<option value="{{ $mata_pelajaran->id }}">{{ $mata_pelajaran->nama_matpel }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="user_id">Nama Guru</label>
						<select name="user_id" id="user_id" class="form-control">
							<option selected disabled>-- Pilih Guru --</option>
							@foreach ($daftar_guru as $guru)
									<option value="{{ $guru->id }}">{{ $guru->name }}</option>
							@endforeach
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" id="create" class="btn btn-primary" onclick="create()">Tambah</button>
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
					<h1 class="box-title">Kelola Guru Mata Pelajaran</h1>
				</div>
				<div class="box-body">
					<div>
						<div class="form-group">
							<label>Group by:</label>
							<br>
							<button class="btn btn-secondary" onclick="loadData('course_id')">Mata Pelajaran</button>
							<button class="btn btn-secondary" onclick="loadData('user_id')">Nama Guru</button>
						</div>
					</div>
					<table id="table" class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th id="col">Mata Pelajaran</th>
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
	let updateURL;
	$(document).ready(() => {
		loadData();
	});

	function tambah() {
		document.getElementById('modalForm').reset();
		let element = updateURL.value.replace('{{ url("guru/edit") }}/', '');
		element = element.split('/');
		
		$('#'+ element[0]).val(element[1]);

		$('#modalTitle').html("Tambah Mata Pelajaran");
		$('#modal').modal('show');
	}

	function create() {
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();

		$.ajax({
			url    : "{{ route('teacher.store') }}",
			method : 'post',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data       : {
				'user_id'   : $('#user_id').val(),
				'course_id'   : $('#course_id').val(),
			},
			dataType   : 'json',
			success    : (res) => {
				edit(updateURL);
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
		updateURL = el;
		let element = updateURL.value.replace('{{ url("guru/edit") }}/', '');

		$.get(
			'{{ url("guru/message") }}/' + element,
			(res) => {
				$('#modalDetailTitle').html(res.message);
			},
			'json'
		)

		$('#tableModal').DataTable().clear().destroy();
		$('#tableModal').DataTable({
			processing: false,
			serverSide: true,
			ajax      : el.value,
			columns   : [
					{width: '90%', data: 'daftar', name: 'daftar'},
					{width: '10%', data: 'action', name: 'action', orderable: false, searchable: false},
				],
			order: [
					[0, 'asc']
				],
			responsive: true,
		});
		$('#modalDetail').modal('show');
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
						edit(updateURL);
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

	function loadData(method = 'course_id') {
		let columns, order, url;
		switch (method) {
			case 'course_id':
				$('#col').html('Mata Pelajaran');
				$('#colModal').html('Nama Guru');
				url = '{{ url('guru/data/course_id') }}';

				columns = [
					{width: '90%', data: 'mata_pelajaran', name: 'mata_pelajaran'},
					{width: '10%', data: 'action', name: 'action', orderable: false, searchable: false},
				];
				break;
				
			case 'user_id':
				$('#col').html('Nama Guru');
				$('#colModal').html('Mata Pelajaran');
				url = '{{ url('guru/data/user_id') }}';

				columns = [
					{width: '90%', data: 'nama_guru', name: 'nama_guru'},
					{width: '10%', data: 'action', name: 'action', orderable: false, searchable: false},
				];
				break;
		}
		$('#table').DataTable().clear().destroy();
		$('#table').DataTable({
			processing: false,
			serverSide: true,
			ajax      : url,
			columns   : columns,
			order: [
					[0, 'asc']
				],
			responsive: true,
		});
	}
</script>
@endsection