@extends('adminlte::page')

{{-- @section('content_header')
    <h1>Profile</h1>
@stop --}}

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-solid box-default">
                <div class="box-header">
                    <h1 class="box-title">Profile</h1>
                </div>
                <div class="box-body">
                    @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name">NIS</label>
                            <input type="text" name="nis" id="nis" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="tahun_masuk">Tahun Masuk</label>
                            <input type="number" name="tahun_masuk" id="tahun_masuk" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="tgl_lahir">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="nama_ayah">Nama Ayah</label>
                            <input type="text" name="nama_ayah" id="nama_ayah" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="nama_ibu">Nama Ibu</label>
                            <input type="text" name="nama_ibu" id="nama_ibu" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="no_hp_ortu">Nomor Telepon Orang Tua</label>
                            <input type="number" name="no_hp_ortu" id="no_hp_ortu" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="5" class="form-control"
                                style="resize: vertical"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="no_hp">Nomor Telepon</label>
                            <input type="number" name="no_hp" id="no_hp" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="photo">Foto Profil</label>
                            <input type="file" name="photo" id="photo" class="form-control">
                        </div>
                        <div class="checkbox">
                            <label for="input_again">
                                <input type="checkbox" name="input_again" id="input_again">Masukan data baru lagi
                                setelah ini
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Siswa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop