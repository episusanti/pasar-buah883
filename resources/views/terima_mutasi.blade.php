@extends('layout.master')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Terima Mutasi</h3>
                </div>
                </div>
            </div>
        </div>
        <!-- partial -->
        <div class="row" style="margin-top:-50px">
            <div class="form-group" style="margin-left:20px">
                <label class="d-flex flex-row align-items-center" for="nama">&nbsp;</label>
                @if(auth()->user()->role == 'Staff Lapangan')
                <a type="button" href="{{url('/terima_mutasi')}}" class="btn btn-success mr-2">Terima Mutasi</a>
                @endif
            </div>
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
                                <th>Qty Terima Mutasi</th>
                                <th>Status Mutasi</th>
                                <th>Aksi</th>

                            </tr>
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
                                <td>{{ $row->terima_mutasi }}</td>
                                <td><label class="badge  {{ $row->status_mutasi == 'Pending' ? 'badge-warning' : 
                                    ($row->status_mutasi == 'Dikirim' ? 'badge-info' : 
                                    ($row->status_mutasi == 'Diterima' ? 'badge-success' : 'badge-secondary')) 
                                    }}">{{ $row->status_mutasi }}</label></td>
                                <td>
                                    @if($row->status_mutasi == 'Dikirim')
                                        <a href="javascript:edit('{{ $row->id_order }}')" class="badge badge-info btn-sm">Edit</a>
                                    @else
                                        -
                                    @endif
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
</div>
<!-- Modal Tambah Mutasi -->
<div class="modal fade" id="modalTambah" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" id="exampleModalLongTitle">Tambah Qty Terima Mutasi</h4>
                        <form action="" method="POST" enctype="multipart/form-data" id="orderForm">
                        <input type="hidden" name="id_order" id="id_order" value="">
                        @csrf
                        <div class="form-group">
                            <label class="d-flex flex-row align-items-center" for="nama">Jumlah Order</label>
                            <input type="text" class="form-control"  name="jumlah" disabled>
                        </div>
                        <div class="form-group">
                            <label class="d-flex flex-row align-items-center" for="nama">Jumlah Mutasi</label>
                            <input type="number" class="form-control" name="jumlah_mutasi" disabled>
                        </div>
                        <div class="form-group">
                            <label class="d-flex flex-row align-items-center" for="nama">Terima Mutasi</label>
                            <input type="number" class="form-control"   name="terima_mutasi" placeholder="Terima Mutasi">
                        </div>
                        <button type="button" class="btn btn-success mr-2" id="btnSimpan">Simpan</button>
                        <a href="" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript">
    function edit(id) {
        $('#exampleModalLongTitle').html("Edit Order");
        $.ajax({
            url: "/edit_order/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                if (data.data == true) {
                    $('[name="jumlah"]').val(data.jumlah);
                    $('[name="jumlah_mutasi"]').val(data.jumlah_mutasi);
                    $('[name="id_order"]').val(data.id_order);
                    $('#modalTambah').modal('show');
                } 
            }
            
        });
    }
    $(document).ready(function () {
    $('#btnSimpan').on('click', function () {
            $.ajax({
                url: "{{ url('update_order') }}",
                method: "POST",
                data: $('#orderForm').serialize(), 
                success: function (response) {
                    $('#orderForm')[0].reset();
                    window.location.reload();
                },
                error: function (error) {
                    alert('Gagal menyimpan data!');
                }
            });
            $('#modalTambah').modal('show');
        });
    });
</script>
@stop