<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mutasi_lapangan;
use Illuminate\Support\Facades\DB;

class mutasi_lp_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = mutasi_lapangan::leftJoin('stok_barang', 'mutasi_lapangan.kode', '=', 'stok_barang.kode')
                ->select('stok_barang.*', 'mutasi_lapangan.*')
                ->orderBy('mutasi_lapangan.id', 'desc')
                ->paginate(25);
        return view('mutasi_lapangan',compact('data'));
    }
    public function terimaMutasi()
    {
        $data = mutasi_lapangan::leftJoin('stok_barang', 'mutasi_lapangan.kode', '=', 'stok_barang.kode')
                ->select('stok_barang.*', 'mutasi_lapangan.*')
                ->where('mutasi_lapangan.status_mutasi','!=','Pending')
                ->orderBy('mutasi_lapangan.id', 'desc')
                ->paginate(25);
        return view('terima_mutasi_lapangan',compact('data'));
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
        $mutasi = mutasi_lapangan::create([
            'kode' => $request->kode,
            'jumlah_mutasi' => $request->jumlah,
        ]);
        return response()->json();
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
        $dt = mutasi_lapangan::leftJoin('stok_barang', 'stok_barang.kode', '=', 'mutasi_lapangan.kode')
            ->select('mutasi_lapangan.*', 'stok_barang.*')
            ->where('mutasi_lapangan.id', $id)
            ->first(); 

        $row = array();
        if ($dt) {
            $row['data'] = TRUE;
            $row['kode'] = $dt->kode;
            $row['barcode'] =  $dt->barcode;
            $row['nama_stok'] =  $dt->nama_stok;
            $row['jumlah_mutasi'] =  $dt->jumlah_mutasi;
            $row['id'] =  $id;


        } else {
            $row['data'] = FALSE;
        }
        echo json_encode($row);
        die;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $terima = $request->terima_mutasi;
        if($terima){
            DB::table('mutasi_lapangan')->where('id', $id)->update([
            'terima_mutasi' => $terima
            ]);
        } else{
            DB::table('mutasi_lapangan')->where('id', $id)->update([
            'kode' => $request->kode,
            'jumlah_mutasi' => $request->jumlah
            ]);
        }
        return redirect()->route('mutasi_lapangan.index')->with('success','Data Mutasi Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('mutasi_lapangan')->where('id',$id)->delete();
        return redirect()->route('mutasi_lapangan.index')->with('success','Berhasil Menghapus Mutasi');
    }
    public function mutasi()
    {
        $mutasiData = mutasi_lapangan::leftJoin('stok_barang', 'stok_barang.kode', '=', 'mutasi_lapangan.kode')
        ->select('mutasi_lapangan.*', 'stok_barang.*')
        ->where('mutasi_lapangan.status_mutasi','Pending')
        ->whereNotNull('mutasi_lapangan.jumlah_mutasi')->get();
        foreach ($mutasiData as $mutasi) {
            $kode = $mutasi->kode;
            $jumlahMutasi = $mutasi->jumlah_mutasi;
            $stok='qty_gudang_kecil';
            $jumlah='qty_lapangan';

            // Melakukan update stok
            DB::table('stok_barang')->where('kode', $kode)->update([
                $stok => DB::raw($stok . '+' . $jumlahMutasi),
                $jumlah => DB::raw($jumlah . '-' . $jumlahMutasi)
            ]);
        }
        $data = mutasi_lapangan::where('status_mutasi', 'Pending')->whereNotNull('jumlah_mutasi')->get();
        if($data->count() > 0){
            DB::table('mutasi_lapangan')->where('status_mutasi','Pending')->whereNotNull('jumlah_mutasi')->update([
                'status_mutasi' => "Dikirim",
                'tgl_mutasi' => now()->format('Y-m-d')
            ]);
            return redirect()->route('mutasi_lapangan.index')->with('success','Berhasil Melakukan Mutasi');
        } else {
            return redirect()->route('mutasi_lapangan.index')->with('error','Belum ada Barang yang Akan di Mutasi Atau Pastikan Qty Mutasi Sudah Anda Isi');
        }
        
    }
    public function confirm()
    {
        $data = mutasi_lapangan::where('status_mutasi', 'Dikirim')->whereNotNull('terima_mutasi')->get();
        if($data->count() > 0){
            DB::table('mutasi_lapangan')->where('status_mutasi','Dikirim')->whereNotNull('terima_mutasi')->update([
                'status_mutasi' => "Diterima",
            ]);
            return redirect('/terimaMutasi_lp')->with('success','Berhasil Terima Mutasi');
        } else {
            return redirect('/terimaMutasi_lp')->with('error','Belum ada Mutasi yang Akan di Terima atau Pastikan Qty Terima Mutasi Sudah Anda Isi');
        }
        
    }
}
