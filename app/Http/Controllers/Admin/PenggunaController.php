<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\User;
use DataTables;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('users')
                        ->select('*')
                        ->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('roleuser', function($data){
                        $rolename = "";
                        if ($data->roleuser == 1) {
                            $rolename = "Admin";    
                        } else {
                            $rolename = "Petugas Parkir";
                        }
                        return $rolename;
                    })
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Edit" class="btn bg-warning btn-sm editUser">Edit <i class="fas fa-edit"></i></a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Delete" class="btn bg-danger btn-sm hapusUser">Hapus <i class="fas fa-trash"></i></a>';
                        $btn = '<center>'.$btn.'</center>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        $judul = "Master Pengguna";
        return view('admin/pengguna/index', compact('judul'));
    }

    public function store(Request $request)
    {
        if(!empty($request->password)){
            User::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'roleuser' => $request->roleuser,
                    'password' => Hash::make($request->password)
                ]
            );
            return response()->json(['success'=>'Pengguna saved successfully.']);
        }else{
            User::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'roleuser' => $request->roleuser
                ]
            );
            return response()->json(['success'=>'Pengguna saved successfully.']);
        }
    }

    public function edit($id)
    {
        $User = User::find($id);
        return response()->json($User);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['success'=>'Product deleted successfully.']);
    }
}
