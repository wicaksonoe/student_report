@extends('adminlte::page')

@section('css')
<style>
	.d-none {
		display: none;
	}
</style>
@endsection

@section('content')
{{-- Meta CSRF --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Modal Notulensi -->
<div class="modal fade" id="notulensi" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title" id="notulensi_judul"></h3>
			</div>
			<div class="modal-body">
				<button class="btn btn-sm btn-primary" id="tambah_notulensi" style="margin-bottom: 1rem"
					onclick="show_notulensi_create(this.value)" value="">
					<span class="fas fa-fw fa-plus-circle"></span> Tambah Notulensi
				</button>
				<table id="table_notulensi" class="table table-bordered table-hover table-striped" style="width: 100%">
					<thead>
						<tr>
							<th>Pertemuan</th>
							<th>Tanggal Pertemuan</th>
							<th>Pokok Bahasan & Tugas</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="form_notulensi_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title" id="form_notulensi_judul"></h3>
			</div>
			<div class="modal-body">
				<form action="" method="post" id="form_notulensi">
					<input type="text" name="id" id="id" hidden readonly>
					<input type="text" name="schedule_id" id="schedule_id" hidden readonly>
					<div class="form-group">
						<label for="guru_pengganti">Guru Pengganti</label>
						<select name="guru_pengganti" id="guru_pengganti" class="form-control">
							<option value="">Tidak ada guru pengganti</option>
							@foreach ($daftar_guru as $item)
									<option value="{{ $item->id }}">{{ $item->name }}</option>
							@endforeach
						</select>
						<span class="help-block">*opsional</span>
					</div>
					<div class="form-group">
						<label for="jam_masuk">Jam Masuk</label>
						<input type="time" name="jam_masuk" id="jam_masuk" class="form-control">
						<span class="help-block">Gunakan format 12 jam. contoh: 07:00 AM untuk jam 7 pagi</span>
					</div>
					<div class="form-group">
						<label for="jam_keluar">Jam Keluar</label>
						<input type="time" name="jam_keluar" id="jam_keluar" class="form-control">
						<span class="help-block">Gunakan format 12 jam. contoh: 03:00 PM untuk jam 3 sore</span>
					</div>
					<div class="form-group">
						<label for="pertemuan">Pertemuan</label>
						<input type="number" name="pertemuan" id="pertemuan" class="form-control">
					</div>
					<div class="form-group">
						<label for="tgl_pertemuan">Tanggal Pertemuan</label>
						<input type="date" name="tgl_pertemuan" id="tgl_pertemuan" class="form-control">
					</div>
					<div class="form-group">
						<label for="pokok_bahasan">Pokok Bahasan</label>
						<textarea name="pokok_bahasan" id="pokok_bahasan" rows="5" class="form-control"></textarea>
					</div>
					<div class="form-group">
						<label for="tugas">Tugas</label>
						<textarea name="tugas" id="tugas" rows="5" class="form-control"></textarea>
						<span class="help-block">*opsional</span>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" id="form_update" class="btn btn-warning" onclick="update()">Update</button>
				<button type="button" id="form_create" class="btn btn-primary" onclick="create()">Save</button>
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
					<h1 class="box-title">Daftar Mata Pelajaran</h1>
				</div>
				<div class="box-body">
					<table id="table" class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Mata Pelajaran</th>
								<th>Kelas</th>
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
		let elem = '#table';
		let url = '{{ url('pertemuan/data/matpel') }}'
		let columns = [
				{width: '80%', data: 'nama_matpel', name: 'nama_matpel'},
				{width: '5%', data: 'kelas', name: 'kelas'},
				{width: '15%', data: 'action', name: 'action', orderable: false, searchable: false},
			];
		loadData(elem, url, columns);
	});
	
	function show_notulensi(id) {
		document.getElementById('form_notulensi').reset();
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();
		$.get(
			'{{ url('pertemuan/message/notulensi') }}/' + id,
			(res) => {
				$('#notulensi_judul').html(res.message);
			},
			'json'
		)
		let elem = '#table_notulensi';
		let url = '{{ url('pertemuan/data/notulensi') }}/' + id
		let columns = [
			{width: '5%', data: 'pertemuan', name: 'pertemuan'},
			{width: '10%', data: 'tgl_pertemuan', name: 'tgl_pertemuan'},
			{width: '60%', data: 'bahasan', name: 'bahasan'},
			{width: '25%', data: 'action', name: 'action', orderable: false, searchable: false},
		];
		loadData(elem, url, columns)
		$('#tambah_notulensi').val(id)
		$('#notulensi').modal('show');
	}

	function show_notulensi_create(id) {
		document.getElementById('form_notulensi').reset();
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();
		$('#form_notulensi_judul').html('Tambah Notulensi Pertemuan');
		$('#form_update').addClass('d-none');
		$('#form_create').removeClass('d-none');
		$('#schedule_id').val(id);
		$('#pertemuan').removeAttr('readonly');
		$('#form_notulensi_modal').modal('show');
	}

	function show_notulensi_edit(id) {
		document.getElementById('form_notulensi').reset();
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();
		$('#form_notulensi_judul').html('Ubah Notulensi Pertemuan');
		$('#form_update').removeClass('d-none');
		$('#form_create').addClass('d-none');
		$('#pertemuan').attr('readonly', 'true');
		show(id);
		$('#form_notulensi_modal').modal('show');
	}

	function show_notulensi_detail(id) {
		document.getElementById('form_notulensi').reset();
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();
		$('#form_notulensi_judul').html('Detail Notulensi Pertemuan');
		$('#form_update').addClass('d-none');
		$('#form_create').addClass('d-none');
		$('#pertemuan').removeAttr('readonly');
		show(id);
		$('#form_notulensi_modal').modal('show');
	}

	function create() {
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();

		$.ajax({
			url    : "{{ route('pertemuan.store') }}",
			method : 'post',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data       : {
				'schedule_id'    : $('#schedule_id').val(),
				'guru_pengganti': $('#guru_pengganti').val(),
				'jam_masuk'     : $('#jam_masuk').val(),
				'jam_keluar'    : $('#jam_keluar').val(),
				'pertemuan'     : $('#pertemuan').val(),
				'tgl_pertemuan' : $('#tgl_pertemuan').val(),
				'pokok_bahasan' : $('#pokok_bahasan').val(),
				'tugas'					: $('#tugas').val()
			},
			dataType   : 'json',
			success    : (res) => {
				let elem = '#table_notulensi';
				let url = '{{ url('pertemuan/data/notulensi') }}/' + res.id
				let columns = [
					{width: '10%', data: 'pertemuan', name: 'pertemuan'},
					{width: '20%', data: 'tgl_pertemuan', name: 'tgl_pertemuan'},
					{width: '50%', data: 'bahasan', name: 'bahasan'},
					{width: '20%', data: 'action', name: 'action', orderable: false, searchable: false},
				];
				loadData(elem, url, columns);
				Swal.fire(
					res.status,
					res.message,
					'success'
				);
				$('#form_notulensi_modal').modal('hide');
				document.getElementById('form_notulensi').reset();
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

	function show(id) {
		$.get(
			"{{ route('pertemuan.show') }}",
			{id: id}
		).done((res) => {
			if (res.status == 'success') {
				$.each(res.data, (key, val) => {
					$('#' + key).val(val);
				});
			}
		});
	}

	function update() {
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();

		$.ajax({
			url    : "{{ route('pertemuan.update') }}",
			method : 'post',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data       : {
				'id'            : $('#id').val(),
				'schedule_id'    : $('#schedule_id').val(),
				'guru_pengganti': $('#guru_pengganti').val(),
				'jam_masuk'     : $('#jam_masuk').val(),
				'jam_keluar'    : $('#jam_keluar').val(),
				'pertemuan'     : $('#pertemuan').val(),
				'tgl_pertemuan' : $('#tgl_pertemuan').val(),
				'pokok_bahasan' : $('#pokok_bahasan').val(),
				'tugas'         : $('#tugas').val()
			},
			dataType   : 'json',
			success    : (res) => {
				let elem = '#table_notulensi';
				let url = '{{ url('pertemuan/data/notulensi') }}/' + res.schedule_id
				let columns = [
					{width: '5%', data: 'pertemuan', name: 'pertemuan'},
					{width: '10%', data: 'tgl_pertemuan', name: 'tgl_pertemuan'},
					{width: '60%', data: 'bahasan', name: 'bahasan'},
					{width: '25%', data: 'action', name: 'action', orderable: false, searchable: false},
				];
				loadData(elem, url, columns)
				Swal.fire(
					res.status,
					res.message,
					'success'
				);
				$('#form_notulensi_modal').modal('hide');
				document.getElementById('form_notulensi').reset();
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

	function loadData(elem, url, columns, order = [0, 'asc']) {
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