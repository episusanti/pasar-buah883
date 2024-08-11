<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\stok_barang;
use App\Models\mutasi;
use Illuminate\Support\Facades\DB;


class stok_barang_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $text = $request->filter;
         $data=stok_barang::where(function ($query) use ($text) {
                $query->where('kode', 'like', '%' . strtolower($text) . '%')
                    ->orWhere('nama_stok', 'like', '%' . strtolower($text) . '%')
                    ->orWhere('barcode', 'like', '%' . strtolower($text) . '%');
        })->paginate(50);
         return view('stok_barang',compact('data'));
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
        //
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
        //
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
        //
    }
    public function updateStok()
    {

        $divisi=auth()->user()->role;
        if($divisi == 'Staff Lapangan'){
            $status='Proses Lapangan';
            $status2='Terima Lapangan';
            $stok='qty_gudang_kecil';
        } else {
            $status='Proses Gudang';
            $status2='Terima Gudang';
            $stok='qty_lapangan';
        }
        $mutasiData = DB::table('mutasi')->select('mutasi_id', 'kode', 'jumlah')->where('status',$status)->get();

        // Melakukan perulangan untuk mengupdate stok
        foreach ($mutasiData as $mutasi) {
            $id = $mutasi->mutasi_id;
            $kode = $mutasi->kode;
            $jumlahMutasi = $mutasi->jumlah;

            // Melakukan update stok
            DB::table('stok_barang')->where('kode', $kode)
            ->update([$stok => DB::raw($stok .'-'. $jumlahMutasi)]);

            $mutasi = mutasi::where('mutasi_id', $id)->update([
                'status' => $status2
            ]);
        }
        return redirect('/terimaMutasi')->with('success','Berhasil Terima Mutasi');

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

    public function getKode(Request $request)
    {
        $text = $request->searchtext;
        $barang = stok_barang::select('kode', 'nama_stok','barcode')
            ->where(function ($query) use ($text) {
                $query->where('kode', 'like', '%' . strtolower($text) . '%')
                    ->orWhere('nama_stok', 'like', '%' . strtolower($text) . '%')
                    ->orWhere('barcode', 'like', '%' . strtolower($text) . '%');
        })->get();

        $output = [];
        $output['total_data'] = count($barang);
        
        foreach ($barang as $list) {
            $row['kode'] = $list->kode;
            $row['barcode'] = $list->barcode;
            $row['nama_stok'] = $list->nama_stok;
            $output['items'][] = $row;
        }

        return response()->json($output);
    }
    public function getLapangan(Request $request)
    {

        $kode=$request->kode;
        $barang = stok_barang::where('kode', $kode)->value('qty_lapangan');
        $data['stok'] = $barang;
        die(json_encode(['success' => true, 'data' => $data]));
    }
}
