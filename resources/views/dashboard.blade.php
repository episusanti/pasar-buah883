@extends('layout.master')
@section('content')
<!-- partial -->
<div class="main-panel">
<div class="content-wrapper">
    <div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">Dashboard</h3>
            <h6 class="font-weight-normal mb-0">Sistem Mutasi Barang Pasar Buah 88</h6>
        </div>
        
        </div>
    </div>
    </div>
    <div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
        <div class="card-people mt-auto">
            <img src="{{asset('asset/images/fruit-3.png')}}" alt="people">
            <div class="weather-info">
            <div class="d-flex">
                <div>
                <h2 class="mb-0 font-weight-normal" style="color:white"></i>88<sup></sup></h2>
                </div>
                <div class="ml-2">
                <h4 class="location font-weight-normal" style="color:white">Pekanbaru</h4>
                <h6 class="font-weight-normal" style="color:white">Riau</h6>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin transparent">
        <div class="row">
        <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-tale">
            <div class="card-body">
                <p class="mb-4">Total Mutasi Bulan Ini</p>
                <p class="fs-30 mb-2">4006</p>
                <p>Periode Bulan</p>
            </div>
            </div>
        </div>
        <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
            <div class="card-body">
                <p class="mb-4">Total Mutasi Bulan Lalu</p>
                <p class="fs-30 mb-2">61344</p>
                <p>Periode Bulan</p>
            </div>
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
            <div class="card card-light-blue">
            <div class="card-body">
                <p class="mb-4">Total Stok Gudang</p>
                <p class="fs-30 mb-2">34040</p>
                <p>Buah dan Barang Lainnya</p>
            </div>
            </div>
        </div>
        <div class="col-md-6 stretch-card transparent">
            <div class="card card-light-danger">
            <div class="card-body">
                <p class="mb-4">Total Stok Lapangan</p>
                <p class="fs-30 mb-2">55679</p>
                <p>Buah dan Barang Lainnya</p>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
@stop