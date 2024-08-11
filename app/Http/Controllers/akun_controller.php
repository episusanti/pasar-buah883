<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class akun_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $divisi=auth()->user()->role;
        if($divisi == 'Supervisor IT'){
            $data=User::paginate(5);
        } else {
            $filter=auth()->user()->name;
            $data=User::where('name',$filter)->paginate(5);
        }
        return view('akun',compact('data'));
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
        $this->validate($request, [
            'name' => 'required|unique:users|min:5',
            'email' => 'required|unique:users|email',
        ]);

        $pengguna = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->input('password')),
            'role' => $request->role,
        ]);

        return redirect()->route('akun.index')->with('success','Akun Berhasil Disimpan');
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
        $divisi=auth()->user()->role;
        $akun = DB::table('users')->where('id',$id)->paginate(5);
        if($divisi == 'Supervisor IT'){
            $data=User::paginate(5);
        } else {
            $filter=auth()->user()->name;
            $data=User::where('name',$filter)->paginate(5);
        }
        return view('akun',compact('data','akun'));
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
        $pengguna = User::findorfail($id);

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $data_pengguna = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->input('password')),
            'role' => $request->role,
        ];

        $pengguna->update($data_pengguna);

        return redirect()->route('akun.index')->with('success','Data Pengguna Berhasil Di Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return redirect('/akun');
    }
}
