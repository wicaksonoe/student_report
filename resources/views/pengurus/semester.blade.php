@extends('adminlte::page')

{{-- @section('content_header')
<h1>Kelola Tahun Akademik</h1>
@stop --}}

@section('content')
{{-- Meta CSRF --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
	<div class="row">
		<div class="col-md-8">
			<div class="box box-solid box-default">
				<div class="box-header">
					<h1 class="box-title">Kelola Tahun Akademik</h1>
				</div>
				<div class="box-body">
					<form class="form-inline">
						<div class="form-group">
							<label for="tahun_akademik">Tahun Akademik</label>
							<input type="text" name="tahun_akademik" id="tahun_akademik" class="form-control"
								placeholder="Format: 2000/2001">
						</div>
						<button type="button" class="btn btn-sm btn-primary" style="margin-left: 4rem" onclick="create()">Buat Tahun
							Akademik</button>
					</form>
					<br>
					<table id="table" class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Semester</th>
								<th>Tahun Akademik</th>
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

		$('form').on('submit', (event) => {
			event.preventDefault();
			create();
		});
	});

	function create() {
		$('.form-control').parent().removeClass('has-error');
		$('.error').remove();

		$.ajax({
			url    : "{{ route('semester.store') }}",
			method : 'post',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data       : {
				'tahun_akademik' : $('#tahun_akademik').val()
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

	function loadData() {
		$('#table').DataTable().clear().destroy();
		$('#table').DataTable({
			processing: false,
			serverSide: true,
			ajax      : '{{ url('akademik/data') }}',
			columns   : [
				{width: '50%', data: 'keterangan', name: 'keterangan'},
				{width: '50%', data: 'tahun_akademik', name: 'tahun_akademik'},
			],
			order: [[1, 'desc']],
			responsive: true,
		});
	}
</script>
@endsection