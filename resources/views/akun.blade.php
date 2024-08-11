@extends('layout.master')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">Pengguna</h3>
                    </div>
                </div>
            </div>
        </div>
        <!-- partial -->
        <div class="row" style="margin-top:-30px">
           @if(auth()->user()->role == 'Supervisor IT')
           <a type="button" class="btn btn-success mb-2 ml-3" data-toggle="modal" data-target="#modalTambah">Tambah Akun</a>
           @endif
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Mutasi Barang</h4>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Divisi</th>
                                <th>Aksi</th>
                            </tr>
                            @php
                                $no = 1;
                            @endphp
                            @foreach($data as $row)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->role }}</td>
                                <td><a href="{{ route('akun.edit', $row->id) }}" class="btn btn-info btn-rounded btn-sm">EDIT</a>
                            <a href="{{ route('akun.destroy', $row->id) }}"
                                id="btn-delete-post"
                                class="btn btn-danger btn-rounded btn-sm"
                                onclick="event.preventDefault(); if(confirm('Yakin akan menghapus Data?')) { document.getElementById('delete-form-{{$row->id}}').submit(); }">
                                DELETE
                                </a>
                                <form id="delete-form-{{$row->id}}" action="{{ route('akun.destroy', $row->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                                </form>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Materi -->
<div class="modal fade" id="modalTambah" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if(isset($akun))
                            <h4 class="card-title">Ubah Akun</h4>
                        @else
                            <h4 class="card-title">Tambah Akun</h4>
                        @endif
                        <form action="{{ isset($akun) ? route('akun.update', $akun->first()->id) : route('akun.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($akun))
                                @method('PUT')
                        @endif
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="name" required name="name" placeholder="Nama Lengkap" value="{{ isset($akun) ? $akun->first()->name : '' }}">
                        </div>
                        <div class="form-group">
                            <label for="nama">Email</label>
                            <input type="email" class="form-control" id="email" required name="email" placeholder="Email"  value="{{ isset($akun) ? $akun->first()->email : '' }}">
                        </div>
                        <div class="form-group">
                            <label for="nama">Password</label>
                            <input type="password" class="form-control" id="password"  name="password" placeholder="{{ isset($akun) ? 'Password diamankan' : 'Password' }}">
                            @if(isset($akun))
                                <label style="color:red">Tidak Perlu diisi jika tidak ingin mengganti password</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="nama">Divisi</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="Staff Lapangan" {{ old('role', isset($akun) ? $akun->first()->role : '') == 'Staff Lapangan' ? 'selected' : '' }}>Staff Lapangan</option>
                                <option value="Staff Gudang" {{ old('role', isset($akun) ? $akun->first()->role : '') == 'Staff Gudang' ? 'selected' : '' }}>Staff Gudang</option>
                                <option value="Staff IT" {{ old('role', isset($akun) ? $akun->first()->role : '') == 'Staff IT' ? 'selected' : '' }}>Staff IT</option>
                                <option value="Supervisor IT" {{ old('role', isset($akun) ? $akun->first()->role : '') == 'Supervisor IT' ? 'selected' : '' }}>Supervisor IT</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success mr-2">Submit</button>
                        <a href="{{ url('akun')}}" type="button" class="btn btn-light" >Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        @if(!empty($akun))
        $('#modalTambah').modal('show'); // Show the modal if editing
        @endif
    });
</script>
@stop