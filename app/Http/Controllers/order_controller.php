<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\order;
use App\Models\mutasi;
use App\Models\stok_barang;
use Illuminate\Support\Facades\DB;
use App\Helpers\RomanNumeralConverter;

class order_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RomanNumeralConverter $converter)
    {
       $barang=stok_barang::paginate(10);
       $data = Order::leftJoin('stok_barang', 'orders.kode', '=', 'stok_barang.kode')
                ->leftJoin('users', 'users.id', '=', 'orders.user_id')
                ->select('orders.*', 'stok_barang.*', 'users.*')
                ->orderBy('orders.id_order', 'desc')
                ->paginate(25);
        $no = Order::select('no_order')
        ->where('status_order', 'Diajukan')
        ->orderBy('id_order', 'desc')
        ->limit(1)
        ->first();
        $month                 = date('m');
        $tahun                 = date('Y');
        $romawi = $converter->convert($month);
        if ($no) {
            $order= $no->no_order;
            $pecah = explode("/",  $order);
            $seq = $pecah[1];
            $no_order = $seq + 1;
        } else {
            $no_order = 1;
        }
        $noorder = 'OR/'.sprintf("%04s", $no_order) . '/' . $romawi . '/' . $tahun; 
        // dd($no_order);
        return view('order',compact('data','barang','noorder'));
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
        $user=auth()->user()->id;
        $order = order::create([
            'kode' => $request->kode,
            'jumlah' => $request->jumlah,
            'user_id' => $user
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
        $dt = order::leftJoin('stok_barang', 'orders.kode', '=', 'stok_barang.kode')
            ->leftJoin('mutasi', 'mutasi.id_order', '=', 'orders.id_order')
            ->select('orders.*', 'stok_barang.*', 'mutasi.*')
            ->where('orders.id_order', $id)
            ->first(); 

        $row = array();
        if ($dt) {
            $row['data'] = TRUE;
            $row['kode'] = $dt->kode;
            $row['barcode'] =  $dt->barcode;
            $row['nama_stok'] =  $dt->nama_stok;
            $row['jumlah'] =  $dt->jumlah;
            $row['jumlah_mutasi'] =  $dt->jumlah_mutasi;
            $row['id_order'] =  $id;


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
        $id = $request->id_order;
        $terima = $request->terima_mutasi;
      
        if($terima){
            DB::table('orders')->where('id_order', $id)->update([
            'terima_mutasi' => $terima
            ]);
        } else{
            DB::table('orders')->where('id_order', $id)->update([
            'kode' => $request->kode,
            'jumlah' => $request->jumlah
            ]);
        }
        return redirect()->route('order.index')->with('success','Data Order Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('orders')->where('id_order',$id)->delete();
        return redirect()->route('order.index')->with('success','Berhasil Menghapus Order');
    }

     public function order(RomanNumeralConverter $converter)
    {
        $data = order::where('status_order','Pending')->get();
        if($data->count() > 0){        
            $no = Order::select('no_order')
            ->where('status_order', 'Diajukan')
            ->orderBy('id_order', 'desc')
            ->limit(1)
            ->first();
            $month                 = date('m');
            $tahun                 = date('Y');
            $romawi = $converter->convert($month);
            if ($no) {
                $order= $no->no_order;
                $pecah = explode("/",  $order);
                $seq = $pecah[1];
                $no_order = $seq + 1;
            } else {
                $no_order = 1;
            }
            $noorder = 'OR/'.sprintf("%04s", $no_order) . '/' . $romawi . '/' . $tahun; 
            $order = order::where('status_order', 'Pending')->update([
                'status_order' => 'Diajukan',
                'no_order' => $noorder,
                'tgl_order' => now()->format('Y-m-d H:i:s')
            ]);

            //Insert Mutasi
            $pengajuan_order = order::where('status_order','Diajukan')->get();
            foreach($pengajuan_order as $list){
                $id = $list->id_order;
                $mutasi = mutasi::create([
                'id_order' => $id
            ]);

            }

            return redirect()->route('order.index')->with('success','Berhasil Melakukan Order');
        } else {
            return redirect()->route('order.index')->with('error','Belum ada Order Baru Semua status Sudah Diajukan');
        }

    }

}
