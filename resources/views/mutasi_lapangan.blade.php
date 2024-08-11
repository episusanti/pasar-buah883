@extends('layout.master')
@section('css')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">Mutasi</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top:-30px">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div style="display:flex;">
                            <div class="form-group" style="margin-left:10px">
                                <label for="nama">Tanggal Mutasi</label>
                                <input type="date" class="form-control" id="tanggal" required name="tanggal"  value="{{ date('Y-m-d') }}" disabled>
                            </div>
                            <div class="form-group" style="margin-left:20px;margin-top:30px">
                                <a type="button" href="{{url('/kirim_mutasi_lp')}}" class="btn btn-warning mr-2">Kirim Mutasi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- partial -->
        <div class="row" style="margin-top:-30px">
           <a type="button" class="btn btn-success mb-2 ml-3" onclick="add_ajax()">Tambah Mutasi</a>
           <!-- <a type="button" href="{{url('/kirim_order')}}" class="btn btn-warning mb-2 ml-3" >Kirim Order</a> -->
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
                                <th>Kode</th>
                                <th>Barcode</th>
                                <th>Keterangan</th>
                                <th>Qty Lapangan</th>
                                <th>Qty Gudang Kecil</th>
                                <th>Qty mutasi</th>
                                <th>Status Mutasi</th>
                                <th>Aksi</th>
                            </tr>
                            @php
                                $no = 1;
                            @endphp
                            @foreach($data as $row)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $row->kode }}</td>
                                <td>{{ $row->barcode }}</td>
                                <td>{{ $row->nama_stok }}</td>
                                <td>{{ $row->qty_lapangan }}</td>
                                <td>{{ $row->qty_gudang_kecil}}</td>
                                <td>{{ $row->jumlah_mutasi }}</td>
                                <td><label class="badge  {{ $row->status_mutasi == 'Pending' ? 'badge-warning' : 
                                    ($row->status_mutasi == 'Dikirim' ? 'badge-info' : 
                                    ($row->status_mutasi == 'Diterima' ? 'badge-success' : 'badge-secondary')) 
                                    }}">{{ $row->status_mutasi }}</label></td>
                                <td>
                                @if($row->status_mutasi == 'Pending')
                                    <a href="javascript:edit('{{ $row->id }}')" class="badge badge-info btn-sm">Edit</a>
                                @else
                                    -
                                @endif
                                <a href="{{ route('mutasi_lapangan.destroy', $row->id) }}"
                                id="btn-delete-post"
                                class="badge badge-danger btn-sm"
                                onclick="event.preventDefault(); if(confirm('Yakin akan menghapus Data?')) { document.getElementById('delete-form-{{$row->id}}').submit(); }">
                                Delete
                                </a>
                                <form id="delete-form-{{$row->id}}" action="{{ route('mutasi_lapangan.destroy', $row->id) }}" method="POST" style="display: none;">
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
                        <h4 class="card-title" id="exampleModalLongTitle">Tambah Mutasi</h4>
                        <form action="" method="POST" enctype="multipart/form-data" id="orderForm">
                        <input type="hidden" name="id" id="id" value="">
                        @csrf
                        <div class="form-group" id="barang">
                            <label class="d-flex flex-row align-items-center">
                                Pilih Barang
                            </label>
                            <div>
                                <select class="form-control" name="kode" id="kode">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="stok">
                            <label class="d-flex flex-row align-items-center" for="nama">Qty Lapangan</label>
                            <input type="number" class="form-control"  name="stok_lapangan"  disabled>
                        </div>
                        <div class="form-group">
                            <label class="d-flex flex-row align-items-center" for="nama">Jumlah Mutasi</label>
                            <input type="number" class="form-control" id="jumlah"  name="jumlah" placeholder="Jumlah">
                        </div>
                        <a href="#" onclick="save()" id="btnSaveAjax" class="btn btn-success mr-2">Simpan</a>
                        <a href="" class="btn btn-light">Keluar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript">
    var method = '';
    var modal = '';

    $(document).ready(function () {
        $("#kode").select2({
            width: "100%",
            closeOnSelect: true,
            placeholder: "Cari kode, barcode atau nama barang",
            ajax: {
                url: "/getKode",
                dataType: "json",
                type: "GET",
                delay: 250,
                data: function(e) {
                    return {
                        searchtext: e.term,
                        page: e.page
                    }
                },
                processResults: function(e, t) {
                    $(e.items).each(function() {
                        this.id = this.kode;
                        this.text = `${this.kode}`;
                    });

                    return t.page = t.page || 1, {
                        results: e.items,
                    }
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 1,
            templateResult: function (data) {
                if (data.loading) return data.text;

                var markup =
                    `<div class='select2-result-repository clearfix'>
                        <div class='select2-result-repository_meta'>
                            <div class='select2-result-repository_title'>
                                ${data.kode} - ${data.barcode} - ${data.nama_stok}
                            </div>
                        </div>
                    </div>`;

                return markup;
            },
            templateSelection: function (data) {
                return data.text;
            }
        }).on("change", function() {
            var selectedAsetId = $(this).val();
            $.ajax({
                url: "/getLapangan",
                dataType: "json",
                type: "GET",
                data: {
                    kode: selectedAsetId
                },
                success: function(response) {
                    if (response.success) {
                        var stok = response.data.stok;
                        $("input[name='stok_lapangan']").val(stok);
                        $('#stok').show();
                    }
                }
            });
        });

    });
    function edit(id) {
        $('#exampleModalLongTitle').html("Edit Order");
        $('#stok').hide();
        method = "{{ url('update_mutasi_lp') }}";
        modal = "update";

        $.ajax({
            url: "/edit_mutasi/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                if (data.data == true) {
                    $('[name="jumlah"]').val(data.jumlah_mutasi);
                    $('[name="id"]').val(data.id);
                    $('[name="kode"] option[value="' + data.kode + '"]').attr('selected', 'selected');
                    $('#kode').append(new Option(data.kode + ' - ' + data.barcode + ' - ' + data.nama_stok, data.kode, false, true));
                    $('.m-select2').select2({width : '100%'});
                    $('#modalTambah').modal('show');

                    $("#kode").select2({
                    width: "100%",
                    closeOnSelect: true,
                    placeholder: "Cari kode, barcode atau nama barang",
                    ajax: {
                        url: "/getKode",
                        dataType: "json",
                        type: "GET",
                        delay: 250,
                        data: function(e) {
                            return {
                                searchtext: e.term,
                                page: e.page
                            }
                        },
                        processResults: function(e, t) {
                            $(e.items).each(function() {
                                this.id = this.kode;
                                this.text = `${this.kode}`;
                            });

                            return t.page = t.page || 1, {
                                results: e.items,
                            }
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    minimumInputLength: 1,
                    templateResult: function (data) {
                        if (data.loading) return data.text;

                        var markup =
                            `<div class='select2-result-repository clearfix'>
                                <div class='select2-result-repository_meta'>
                                    <div class='select2-result-repository_title'>
                                        ${data.kode} - ${data.barcode} - ${data.nama_stok}
                                    </div>
                                </div>
                            </div>`;

                        return markup;
                    },
                    templateSelection: function (data) {
                        return data.text;
                    }
                });

                } 
            },
            
        });
    }
    function save() {
        $.ajax({
                url: method,
                method: "POST",
                data: $('#orderForm').serialize(), 
                success: function (response) {
                    if (modal === "update") {
                        $('#modalTambah').modal('hide');
                        window.location.reload();
                    } else {
                        $('#orderForm')[0].reset();
                        $('#kode').val('').trigger('change');
                    }
                },
                error: function (error) {
                    alert('Gagal menyimpan data!');
                }
            });
    }
    function add_ajax() {
        modal="add"
        method = "{{ route('mutasi_lapangan.store') }}";
        $('#stok').hide();
        $('#modalTambah').modal('show');
    }
</script>
@stop