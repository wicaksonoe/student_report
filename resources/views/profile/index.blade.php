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
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $user_data->name }}">
                            </div>
                            <div class="form-group">
                                <label for="group_id">Wali Kelas</label>
                                <select name="group_id" id="group_id" class="form-control">
                                    <option>-- Pilih Kelas --</option>
                                    @foreach ($daftar_kelas as $kelas)
                                        <option value="{{ $kelas->id }}"
                                            @if($user_data->group_id == $kelas->id ) selected @endif>{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="email">E-Mail</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ $user_data->email }}">
                            </div>
                            <div class="form-group">
                                <label for="photo">Foto Profil</label>
                                <input type="file" name="photo" id="photo" class="form-control">
                            </div>
                            <br><br>
                            <div class="form-group">
                                <label for="password">Masukan Password Anda Untuk Konfirmasi</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        @if (session('error'))
            window.alert('{{ session('error') }}');
        @endif
    </script>
@stop