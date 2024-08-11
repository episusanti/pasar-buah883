<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mutasi;
use App\Models\stok_barang;
use App\Models\order;
use Illuminate\Support\Facades\DB;

class mutasi_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $data = mutasi::leftJoin('orders as o', 'o.id_order', '=', 'mutasi.id_order')
        ->leftJoin('stok_barang as sb', 'o.kode', '=', 'sb.kode')
        ->leftJoin('users as u', 'o.user_id', '=', 'u.id')
        ->select('mutasi.*', 'o.*', 'sb.*', 'u.*')
        ->orderBy('o.id_order', 'desc')
        ->paginate(25);
       return view('mutasi',compact('data'));
    }
    public function terimaMutasi()
    {
       $data = mutasi::leftJoin('orders as o', 'o.id_order', '=', 'mutasi.id_order')
        ->leftJoin('stok_barang as sb', 'o.kode', '=', 'sb.kode')
        ->leftJoin('users as u', 'o.user_id', '=', 'u.id')
        ->select('mutasi.*', 'o.*', 'sb.*', 'u.*')->where('status_mutasi','!=','Pending')
        ->paginate(25);
         return view('terima_mutasi',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $divisi=auth()->user()->role;
        if($divisi == 'Staff Lapangan'){
            $asal='Lapangan';
            $tujuan='Gudang';
            $status='Mutasi Lapangan';
        } else {
            $asal='Gudang';
            $tujuan='Lapangan';
            $status='Mutasi Gudang';
        }
        $mutasi = mutasi::create([
            'kode' => $request->kode,
            'jumlah' => $request->jumlah,
            'asal_mutasi' => $asal,
            'tujuan_mutasi' => $tujuan,
            'status' => $status
        ]);

        return redirect()->route('mutasi.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = mutasi::leftJoin('orders as o', 'o.id_order', '=', 'mutasi.id_order')
        ->leftJoin('stok_barang as sb', 'o.kode', '=', 'sb.kode')
        ->leftJoin('users as u', 'o.user_id', '=', 'u.id')
        ->select('mutasi.*', 'o.*', 'sb.*', 'u.*')
        ->orderBy('o.id_order', 'desc')
        ->paginate(25);
        $mutasi = mutasi::leftJoin('orders as o', 'o.id_order', '=', 'mutasi.id_order')
        ->leftJoin('stok_barang as sb', 'o.kode', '=', 'sb.kode')
        ->leftJoin('users as u', 'o.user_id', '=', 'u.id')
        ->select('mutasi.*', 'o.*', 'sb.*', 'u.*')->where('id_mutasi',$id)
        ->first();
       return view('mutasi',compact('mutasi','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::table('mutasi')->where('id_mutasi',$id)->update([
                'jumlah_mutasi' => $request->jumlah
        ]);
        return redirect()->route('mutasi.index')->with('success','Berhasil Menambahkan Qty Mutasi');
    }

    public function updateMutasiLapangan(Request $request)
    {
        $divisi=auth()->user()->role;
        if($divisi == 'Staff Lapangan'){
            $status='Mutasi Lapangan';
            $status2='Proses Gudang';
        } else {
            $status='Mutasi Gudang';
            $status2='Proses Lapangan';
        }
        $mutasi = mutasi::where('status', $status)->update([
            'nomer_mutasi' => $request->nomer_mutasi,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'status' => $status2
        ]);

        return redirect()->route('mutasi.index')->with('success','Berhasil Melakukan Mutasi');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function mutasi()
    {
        $mutasiData = mutasi::leftJoin('orders as o', 'o.id_order', '=', 'mutasi.id_order')
        ->leftJoin('stok_barang as sb', 'o.kode', '=', 'sb.kode')
        ->select('mutasi.*', 'o.*', 'sb.*')
        ->where('status_mutasi','Pending')
        ->whereNotNull('mutasi.jumlah_mutasi')->get();
        foreach ($mutasiData as $mutasi) {
            $id = $mutasi->id_mutasi;
            $kode = $mutasi->kode;
            $jumlahMutasi = $mutasi->jumlah_mutasi;
            $stok='qty_gudang_kecil';
            $jumlah='qty_lapangan';

            // Melakukan update stok
            DB::table('stok_barang')->where('kode', $kode)->update([
                $stok => DB::raw($stok . '-' . $jumlahMutasi),
                $jumlah => DB::raw($jumlah . '+' . $jumlahMutasi)
            ]);
        }
        $data = mutasi::where('status_mutasi', 'Pending')->whereNotNull('jumlah_mutasi')->get();
        if($data->count() > 0){
            DB::table('mutasi')->where('status_mutasi','Pending')->whereNotNull('mutasi.jumlah_mutasi')->update([
                'status_mutasi' => "Dikirim",
                'tgl_mutasi' => now()->format('Y-m-d')
            ]);
             DB::table('mutasi')->where('status_mutasi','Pending')->whereNotNull('mutasi.jumlah_mutasi')->update([
                'status_mutasi' => "Dikirim",
                'tgl_mutasi' => now()->format('Y-m-d')
            ]);
            return redirect()->route('mutasi.index')->with('success','Berhasil Melakukan Mutasi');
        } else {
            return redirect()->route('mutasi.index')->with('error','Belum ada Order yang Akan di Mutasi Atau Pastikan Qty Mutasi Sudah Anda Isi');
        }
        
    }
    public function updateMutasi()
    {
        $data = mutasi::leftJoin('orders', 'orders.id_order', '=', 'mutasi.id_order')
        ->where('mutasi.status_mutasi', 'Dikirim')->whereNotNull('orders.terima_mutasi')->get();
        if($data->count() > 0){
            DB::table('mutasi')->where('status_mutasi','Dikirim')->update([
                'status_mutasi' => "Diterima",
            ]);
            DB::table('orders')
                ->leftJoin('mutasi', 'mutasi.id_order', '=', 'orders.id_order')
                ->where('mutasi.status_mutasi', 'Diterima')
                ->where('orders.status_order', 'Diajukan')
                ->update([
                    'orders.status_order' => 'Selesai'
                ]);
            return redirect('/terimaMutasi')->with('success','Berhasil Melakukan Mutasi');
        } else {
            return redirect('/terimaMutasi')->with('error','Belum ada Mutasi yang Akan di Terima atau Pastikan Qty Terima Mutasi Sudah Anda Isi');
        }
        
    }
}
