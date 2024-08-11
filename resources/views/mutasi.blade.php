@extends('layout.master')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">Mutasi Barang</h3>
                    </div>
                </div>
            </div>
        </div>
        <!-- partial -->
        <div class="row" style="margin-top:-30px">
          @if(auth()->user()->role == 'Staff Lapangan')
           <a type="button" class="btn btn-success mb-2 ml-3" data-toggle="modal" data-target="#modalTambah">Tambah Mutasi</a>
          @endif
           <a type="button" href="{{url('/kirim_mutasi')}}" class="btn btn-success mb-2 ml-3">Kirim Mutasi</a>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Mutasi Barang</h4>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger mt-3" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>No</th>
                                <th>No Order</th>
                                <th>Kode</th>
                                <th>Nama Stok</th>
                                <th>Barcode</th>
                                <th>Qty Lapangan</th>
                                <th>Qty Gudang Kecil</th>
                                <th>Qty Order</th>
                                <th>Tanggal Order</th>
                                <th>Request By</th>
                                <th>Qty Mutasi</th>
                                <th>Status Mutasi</th>
                                <th>Aksi</th>
                            </tr>
                            @if(auth()->user()->role == 'Staff Gudang')
                            @php
                                $no = 1;
                            @endphp
                            @foreach($data as $row)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $row->no_order }}</td>
                                <td>{{ $row->kode }}</td>
                                <td>{{ $row->nama_stok }}</td>
                                <td>{{ $row->barcode }}</td>
                                <td>{{ $row->qty_lapangan }}</td>
                                <td>{{ $row->qty_gudang_kecil}}</td>
                                <td>{{ $row->jumlah }}</td>
                                <td>{{ $row->tgl_order }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->jumlah_mutasi }}</td>
                                <td><label class="badge  {{ $row->status_mutasi == 'Pending' ? 'badge-warning' : 
                                    ($row->status_mutasi == 'Dikirim' ? 'badge-info' : 
                                    ($row->status_mutasi == 'Diterima' ? 'badge-success' : 'badge-secondary')) 
                                    }}">{{ $row->status_mutasi }}</label></td>
                                <td><a href="{{ route('mutasi.edit', $row->id_mutasi) }}" class="badge badge-info">EDIT</a></td>
                            </tr>
                        @endforeach
                        </table>
                    {{ $data->links() }}
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Tambah Mutasi -->
<div class="modal fade" id="modalTambah" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" id="exampleModalLongTitle">Tambah Qty Mutasi</h4>
                        <form action="{{ isset($mutasi) ? route('mutasi.update', ['mutasi' => $mutasi->id_mutasi]) : '' }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="d-flex flex-row align-items-center" for="nama">Barang</label>
                            <input type="text" class="form-control" value="{{ isset($mutasi) ? $mutasi->kode . ' - ' . $mutasi->barcode . ' - ' . $mutasi->nama_stok : '' }}" disabled>
                        </div>
                        <div class="form-group">
                            <label class="d-flex flex-row align-items-center" for="nama">Jumlah Order</label>
                            <input type="number" class="form-control" value="{{ isset($mutasi)? $mutasi->jumlah : ''}}" disabled>
                        </div>
                        <div class="form-group">
                            <label class="d-flex flex-row align-items-center" for="nama">Jumlah Mutasi</label>
                            <input type="number" class="form-control" id="jumlah"  name="jumlah" placeholder="Jumlah Mutasi">
                        </div>
                        <button type="submit" class="btn btn-success mr-2">Simpan</button>
                        <a href="/mutasi" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        @if(isset($mutasi))
        $('#modalTambah').modal('show');
        @endif
    });
</script>
@stop