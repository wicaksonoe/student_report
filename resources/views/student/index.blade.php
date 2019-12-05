@extends('adminlte::page')

{{-- @section('content_header')
    <h1>Profile</h1>
@stop --}}

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10">
            <div class="box box-solid box-default">
                <div class="box-header">
                    <h1 class="box-title">Daftar Siswa di Kelas xxx</h1>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">NIS</th>
                                    <th class="text-center">Foto</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Tahun Masuk</th>
                                    <th class="text-center" colspan="3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($student_datas as $key => $student)
                                <tr>
                                    <td style="vertical-align: middle" class="text-center">{{$key + 1}}</td>
                                    <td style="vertical-align: middle">{{$student->nis}}</td>
                                    <td style="vertical-align: middle">
                                        <img src="{{asset('storage/'.$student->photo)}}"
                                            alt="{{'foto '.$student->name}}" style="max-width: 100px">
                                    </td>
                                    <td style="vertical-align: middle">{{$student->name}}</td>
                                    <td style="vertical-align: middle">{{$student->tahun_masuk}}</td>
                                    <td style="vertical-align: middle">
                                        <a href="{{route('student.show', $student->id)}}"
                                            class="btn btn-sm btn-primary">Detail</a>
                                    </td>
                                    <td style="vertical-align: middle">
                                        <a href="{{route('student.edit', $student->id)}}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                    </td>
                                    <td style="vertical-align: middle">
                                        <form action="{{route('student.destroy', $student->id)}}" method="post">
                                            {{csrf_field()}}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop