@extends('layout.master')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">Stok Barang</h3>
                    </div>
                </div>
            </div>
        </div>
        <!-- partial -->
        <div class="row" style="margin-top:-30px">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title">Stok Barang</h4>
                            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                                <form class="d-flex" action="{{ route('stok_barang.index') }}" method="GET" id="searchForm">
                                    <input type="text" class="form-control mb-2" placeholder="cari kode, barcode atau nama barang" name="filter" id="filterInput" value="{{ app('request')->input('filter') }}">
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Stok</th>
                                    <th>Barcode</th>
                                    <th>Qty Lapangan</th>
                                    <th>Qty Gudang Kecil</th>
                                    <th>Qty Gudang Besar</th>
                                </tr>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach($data as $row)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $row->kode }}</td>
                                    <td>{{ $row->nama_stok }}</td>
                                    <td>{{ $row->barcode ? $row->barcode : '-' }}</td>
                                    <td>{{ $row->qty_lapangan }}</td>
                                    <td>{{ $row->qty_gudang_kecil}}</td>
                                    <td>{{ $row->qty_gudang_besar }}</td>
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
   document.getElementById('searchForm').addEventListener('submit', function () {
        var filterInput = document.getElementById('filterInput');
        localStorage.setItem('savedFilter', filterInput.value);
    });

    // Mendapatkan nilai input teks saat halaman dimuat
    var filterInput = document.getElementById('filterInput');
    var savedFilter = localStorage.getItem('savedFilter');
    if (savedFilter) {
        filterInput.value = savedFilter;
    }

    // Mengirimkan formulir saat nilai input berubah
    filterInput.addEventListener('input', function () {
        document.getElementById('searchForm').submit();
    });
</script>
@stop