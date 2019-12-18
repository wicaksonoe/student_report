@extends('adminlte::page')

{{-- @section('content_header')
    <h1>Profile</h1>
@stop --}}

@section('content')
{{-- Meta CSRF --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Modal Detail --}}
<div class="modal fade" id="modalDetailSiswa" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">Detail Siswa
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</h3>
			</div>
			<div class="modal-body">
				<table class="table table-bordered table-hover table-striped">
					<tbody id="tabelBody">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalSiswa" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetFormModal()">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title" id="modalSiswaTitle"></h3>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" id="modalSiswaForm">
					<input type="number" name="id" id="id_siswa" hidden>
					@foreach ($input as $key => $prop)
					<div class="form-group">
						<label for="{{ $key }}">{{ $prop['label'] }}</label>
						@if ($prop['type'] == 'textarea')
						<textarea name="{{ $key }}" id="{{ $key }}" rows="5" class="form-control"
							style="resize: vertical"></textarea>
						@elseif ($prop['type'] == 'radio')
						<br>
						<div class="form-group">
							<label class="radio-inline">
								<input type="radio" name="jenis_kelamin" id="P" value="P">Perempuan
							</label>
							<label class="radio-inline">
								<input type="radio" name="jenis_kelamin" id="L" value="L">Laki-Laki
							</label>
						</div>
						@elseif ($key == 'group_id')
						<select name="group_id" id="group_id" class="form-control">
							<option>-- Pilih Kelas --</option>
							@foreach ($kelas as $item)
									<option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
							@endforeach
						</select>
						@else
						<input type="{{ $prop['type'] }}" name="{{ $key }}" id="{{ $key }}" class="form-control">
						@endif
					</div>
					@endforeach
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetFormModal()">Kembali</button>
				<button type="button" class="btn btn-warning" id="update" onclick="updateDataSiswa(this)" value="">Update</button>
				<button type="button" class="btn btn-primary" id="create" onclick="storeDataSiswa()">Tambah Siswa</button>
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
					<h1 class="box-title">Daftar Siswa di Kelas {{$user->kelas->nama_kelas}}</h1>
				</div>
				<div class="box-body">
					<div style="margin-bottom: 1rem">
						<button class="btn btn-primary" onclick="tambahSiswa()"><span class="fas fa-fw fa-plus-circle"></span>
							Tambah Siswa</button>
					</div>
					<div class="">
						<table id="table" class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th class="text-center">NIS</th>
									<th class="text-center">Foto</th>
									<th class="text-center">Nama</th>
									<th class="text-center">Tahun Masuk</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop

@section('js')
<script>
	let modalSiswaTitle = $('#modalSiswaTitle');
	let modalSiswaForm = $('#modalSiswaForm');

	$(document).ready(() => {
		createDataTable();
	})

	function resetFormModal() {
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();
		document.getElementById('modalSiswaForm').reset();
	}

	function createDataTable() {
		$('#table').DataTable().clear().destroy();
		$('#table').DataTable({
			processing: false,
			serverSide: true,
			ajax      : '{{ route('student.show') }}',
			columns   : [
				{width: '10%', data: 'nis', name: 'nis'},
				{width: '10%', data: 'foto', name: 'foto', orderable: false, searchable: false},
				{width: '40%', data: 'name', name: 'name'},
				{width: '10%', data: 'tahun_masuk', name: 'tahun_masuk'},
				{width: '30%', data: 'action', name: 'action', orderable: false, searchable: false},
			],
			responsive: true,
		});
	}

	function tambahSiswa() {
		try {
			resetFormModal();
			modalSiswaTitle.html("Tambah Siswa");

			$('[name="group_id"]').val({{ $user->group_id }});
			$('#update').attr('disabled', true);
			$('#create').attr('disabled', false);
		} catch (error) {
			console.log(error);
		} finally {
			$('#modalSiswa').modal('show');
		}
	}

	function storeDataSiswa() {
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();

		let dataUpload = new FormData();
		let photoFile = $('#photo').prop('files')[0];

		@foreach ($input as $key => $prop)
			@if ($key == 'photo')
				dataUpload.append('{{ $key }}', photoFile);
			@elseif ($key == 'jenis_kelamin')
				dataUpload.append('{{ $key }}', $('[name="{{ $key }}"]:checked').val());
			@else
				dataUpload.append('{{ $key }}', $('[name="{{ $key }}"]').val());
			@endif
		@endforeach

		$.ajax({
			url    : '{{ route("student.store") }}',
			method : 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data       : dataUpload,
			dataType   : 'json',
			processData: false,
			contentType: false,
			success    : (res) => {
				createDataTable();
				Swal.fire(
					res.status,
					res.message,
					'success'
				);
				$('#modalSiswa').modal('hide');
				document.getElementById('modalSiswaForm').reset();
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
			},
		});
	}

	function detailDataSiswa(el) {
		$('#tabelBody').children().remove();
		$.get(
			$(el).val(),
			(response) => {
				try {
					// populate data
					$.each(response, (key, val) => {
						let kolomNama;
						let kolomIsi;

						switch (key) {
							case 'nis':
								kolomNama = 'NIS';
								kolomIsi = val;
								break;

							case 'name':
								kolomNama = 'Nama';
								kolomIsi = val;
								break;

							case 'jenis_kelamin':
								kolomNama = 'Jenis Kelamin';
								kolomIsi = (val == 'L' ? 'Laki-laki' : 'Perempuan');
								break;

							case 'tahun_masuk':
								kolomNama = 'Tahun Masuk';
								kolomIsi = val;
								break;

							case 'tempat_lahir':
								kolomNama = 'Tempat Lahir';
								kolomIsi = val;
								break;

							case 'tgl_lahir':
								kolomNama = 'Tanggal Lahir';
								kolomIsi = val;
								break;

							case 'nama_ayah':
								kolomNama = 'Nama Ayah';
								kolomIsi = val;
								break;

							case 'nama_ibu':
								kolomNama = 'Nama Ibu';
								kolomIsi = val;
								break;

							case 'no_hp_ortu':
								kolomNama = 'Nomor HP Orang Tua';
								kolomIsi = val;
								break;

							case 'alamat':
								kolomNama = 'Alamat';
								kolomIsi = val;
								break;

							case 'no_hp':
								kolomNama = 'Nomor HP';
								kolomIsi = val;
								break;

							case 'photo':
								kolomNama = 'Foto';
								kolomIsi = '<img src="storage/' + val + '" alt="foto"	style="max-width: 100px">';
								break;
						}
						
						if (kolomNama == null) {
							return;
						} else {
							$('#tabelBody').append(
								'<tr>' +
									'<td style="width: 20rem">' + kolomNama + '</td>' +
									'<td>' + kolomIsi + '</td>' +
								'</tr>'
								);
						}
					});

				} catch (error) {
					// catch error
					console.log(error);
				} finally {
					// display modal
					$('#modalDetailSiswa').modal('show');
				}
			},
			'json'
		);
	}

	function editDataSiswa(el) {
		resetFormModal();
		document.getElementById('modalSiswaForm').reset();
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();

		$.get(
			$(el).val(),
			(response) => {
				try {
					// populate data
					$('#update').attr('disabled', false);
					$('#create').attr('disabled', true);

					$('#update').val(response.id);
					$.each(response, (key, val) => {
						if (key == 'jenis_kelamin') {
							if (val == 'L') {
								$('[name="jenis_kelamin"][value="L"]').prop('checked', true);
							} else {
								$('[name="jenis_kelamin"][value="P"]').prop('checked', true);
							}
						} else {
							$('[name="' + key + '"]').val(val);
						}
					});
				} catch (error) {
					console.log(error);
				} finally {
					$('#modalSiswa').modal('show');
				}
			},
			'json'
		);
	}

	function updateDataSiswa(el) {
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();

		let dataUpdate = new FormData();
		let photoFile = $('#photo').prop('files')[0];

		@foreach ($input as $key => $prop)
			@if ($key == 'photo')
				dataUpdate.append('{{ $key }}', photoFile);
			@else
				dataUpdate.append('{{ $key }}', $('[name="{{ $key }}"]').val());
			@endif
		@endforeach

		dataUpdate.append('id', $('#update').val());

		$.ajax({
			url    : '{{ route("student.update") }}',
			method : 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data       : dataUpdate,
			dataType   : 'json',
			processData: false,
			contentType: false,
			success    : (res) => {
				createDataTable();
				Swal.fire(
					res.status,
					res.message,
					'success'
				);
				$('#modalSiswa').modal('hide');
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
			},
		});
	}

	function hapusDataSiswa(el) {
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
					url    : $(el).val(),
					method : 'DELETE',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: (res) => {
						createDataTable();
						Swal.fire(
							"Terhapus",
							"Data berhasil dihapus!",
							'success'
						);
					},
					error  : (res) => {
						console.log(res);
					}
				});
			}
		});
		
	}
</script>
@endsection