@extends('layout.master')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        @if(auth()->user()->role == 'Staff Lapangan')
                            <h3 class="font-weight-bold">Mutasi Lapangan </h3>
                        @else
                            <h3 class="font-weight-bold">Mutasi Gudang </h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top:-30px">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="forms-sample" action="{{ route('updateMutasiLapangan') }}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <div style="display:flex;">
                            <div class="form-group">
                                <label for="nama">Nomer Mutasi</label>
                                <input type="text" class="form-control" id="nomer_mutasi" required name="nomer_mutasi" placeholder="Nomer Mutasi">
                            </div>
                            <div class="form-group" style="margin-left:20px">
                                <label for="nama">Tanggal Mutasi</label>
                                <input type="date" class="form-control" id="tanggal" required name="tanggal" placeholder="Tanggal Mutasi">
                            </div>
                            <div class="form-group" style="margin-left:20px">
                                <label for="nama">Keterangan</label>
                                <input type="text" class="form-control" id="keterangan" required name="keterangan" placeholder="Keterangan" style="width:450px">
                            </div>
                            <div class="form-group" style="margin-left:20px">
                                <label for="nama">-------------</label>
                                <button type="submit" class="btn btn-success mr-2">Kirim</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- partial -->
        <div class="row" style="margin-top:-30px">
           <a type="button" class="btn btn-success mb-2 ml-3" data-toggle="modal" data-target="#modalTambah">Tambah Mutasi</a>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Mutasi Barang</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Stok</th>
                                <th>Barcode</th>
                                <th>Jumlah Mutasi</th>
                                <th>Asal Mutasi</th>
                                <th>Tujuan Mutasi</th>
                            </tr>
                            @php
                                $no = 1;
                            @endphp
                            @foreach($data as $row)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $row->kode }}</td>
                                <td>{{ $row->nama_stok }}</td>
                                <td>{!! DNS1D::getBarcodeHTML((string)$row->barcode, 'UPCA', 1.5, 30) !!}
                                    {{ $row->barcode }}
                                </td>
                                <td>{{ $row->jumlah}}</td>
                                <td>{{ $row->asal_mutasi }}</td>
                                <td>{{ $row->tujuan_mutasi }}</td>
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
                        <h4 class="card-title">Tambah Mutasi</h4>
                        <form action="{{ route('mutasi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="d-flex flex-row align-items-center" for="nama">Nama</label>
                            <select name="kode" class="form-control m-input m-select2">
                                <option value="">Pilih Barang</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->kode }}">{{ $item->kode . ' - ' . $item->nama_stok }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="d-flex flex-row align-items-center" for="nama">Jumlah Mutasi</label>
                            <input type="number" class="form-control" id="jumlah"  name="jumlah" placeholder="Jumlah">
                        </div>
                        <button type="submit" class="btn btn-success mr-2">Submit</button>
                        <a href="" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop