<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Raport Siswa</title>
	{{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<style>
		body {
			font-size: 0.8rem;
		}
	</style>
</head>

<body>
	<br>
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<h2 class="text-center"><strong>Laporan Hasil Belajar Siswa</strong></h2>
				<br><br>
				<table class="table">
					<tr>
						<td style="border: 0; width: 20%; padding: 0.3rem">
							<p style="margin: 0"><strong>Nama Siswa</strong></p>
						</td>
						<td style="border: 0; padding: 0.3rem">
							<p style="margin: 0">: {{ $data['nama_siswa'] }}</p>
						</td>
					</tr>
					<tr>
						<td style="border: 0; padding: 0.3rem">
							<p style="margin: 0"><strong>Kelas</strong></p>
						</td>
						<td style="border: 0; padding: 0.3rem">
							<p style="margin: 0">: {{ $data['nama_kelas'] }}</p>
						</td>
					</tr>
					<tr>
						<td style="border: 0; padding: 0.3rem">
							<p style="margin: 0"><strong>Semester</strong></p>
						</td>
						<td style="border: 0; padding: 0.3rem">
							<p style="margin: 0">: {{ $data['semester'] }}</p>
						</td>
					</tr>
					<tr>
						<td style="border: 0; padding: 0.3rem">
							<p style="margin: 0"><strong>Tahun Akademik</strong></p>
						</td>
						<td style="border: 0; padding: 0.3rem">
							<p style="margin: 0">: {{ $data['tahun_akademik'] }}</p>
						</td>
					</tr>
				</table>
				<br>

				@foreach ($data['rekap_nilai'] as $key => $items)
				<table class="table table-bordered">
					<thead>
						<tr>
							<th colspan="4"><strong>{{ $key }}</strong></th>
						</tr>
					</thead>
					<thead>
						<tr>
							<th class="text-center" style="width: 60%">Mata Pelajaran</th>
							<th class="text-center" style="width: 10%">KKM</th>
							<th class="text-center" style="width: 10%">Nilai</th>
							<th class="text-center" style="width: 20%">Keterangan</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($items as $item)
						<tr>
							<td>
								<strong>{{ $item['nama_matpel'] }}</strong><br>
								<i>Guru: {{ $item['guru_matpel'] }}</i>
							</td>
							<td class="text-center" style="vertical-align: middle">{{ $item['kkm_matpel'] }}</td>
							<td class="text-center" style="vertical-align: middle">{{ $item['nilai'] }}</td>
							<td class="text-center" style="vertical-align: middle">
								@if ($item['nilai'] >= $item['kkm_matpel'])
								<strong>LULUS</strong>
								@else
								<strong>TIDAK LULUS</strong>
								@endif
							</td>
						</tr>
						@endforeach
					</tbody>
				</table><br>
				@endforeach

				<br>
				<table class="table">
					<tbody>
						<tr>
							<td style="border: 0; width: 40%">
								<div class="text-center">Mengetahui,</div>
								<div class="text-center">Orang Tua/Wali Murid</div>
								<br><br><br><br><br><br>
								<div class="text-center">(...........................................)</div>
							</td>
							<td style="border: 0; width: 10%"></td>
							<td style="border: 0; width: 10%"></td>
							<td style="border: 0; width: 40%">
								<div class="text-center">Wali Kelas,</div>
								<br><br><br><br><br><br><br>
								<div class="text-center">(    {{ $data['wali_kelas'] }}    )</div>
							</td>
						</tr>
					</tbody>
				</table>
				<br><br>
			</div>
		</div>
	</div>
	{{-- <script src="{{ asset('js/app.js') }}"></script> --}}
</body>

</html>