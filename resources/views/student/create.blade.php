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
                    <form action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @foreach ($input as $key => $prop)
                            <div class="form-group {{ $errors->has($key) ? 'has-error' : '' }}">
                                <label for="{{ $key }}">{{ $prop['label'] }}</label>
                                @if ($prop['type'] == 'textarea')
                                    <textarea name="{{ $key }}" id="{{ $key }}" rows="5" class="form-control" style="resize: vertical"></textarea>
                                @else
                                    <input type="{{ $prop['type'] }}" name="{{ $key }}" id="{{ $key }}" class="form-control">
                                @endif

                                @if ($errors->has($key))
                                    <span class="help-block">
                                        {{ $prop['error'] }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                        <div class="checkbox">
                            <label for="input_again">
                                <input type="checkbox" name="input_again" id="input_again">Masukan data baru lagi
                                setelah ini
                            </label>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Tambah Siswa</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop