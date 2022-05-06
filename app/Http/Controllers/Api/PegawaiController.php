<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::all();

        if (count($pegawai) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function show($id)
    {
        $pegawai = Pegawai::where('id_pegawai',$id)->first();

        if(!is_null($pegawai)) {
            return response([
                'message' => 'Retrieve Employee Success',
                'data' => $pegawai
            ], 200); 
        }

        return response([
            'message' => 'Employee Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData=$request->all();
        $validate=Validator::make($storeData, [
            'nama_pegawai' => 'required|string',
            'alamat_pegawai' => 'required|string',
            'tanggal_lahir_pegawai' => 'required',
            'jenis_kelamin_pegawai' => 'required',
            'email_pegawai' => 'required|string|email|unique:customers,email_customer|unique:pegawais,email_pegawai|unique:drivers,email_driver',
            'no_telp_pegawai' => 'required|string',
            'foto_pegawai' => 'required|image|mimes:jpeg,jpg,png',
            'jabatan_pegawai' => 'required|string',
        ]); 

        $birthdate = date('dmY', strtotime($request->tanggal_lahir_pegawai));
        $passwordPegawai = bcrypt($birthdate);
        $photoEmployee = $request->foto_pegawai->store('foto_pegawai',['disk' => 'public']);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 

        $pegawai=Pegawai::create([
            'nama_pegawai' => $request->nama_pegawai,
            'alamat_pegawai' => $request->alamat_pegawai,
            'tanggal_lahir_pegawai' => $request->tanggal_lahir_pegawai,
            'jenis_kelamin_pegawai' => $request->jenis_kelamin_pegawai,
            'email_pegawai' => $request->email_pegawai,
            'password_pegawai' => $passwordPegawai,
            'no_telp_pegawai' => $request->no_telp_pegawai,
            'foto_pegawai' => $photoEmployee,
            'jabatan_pegawai' => $request->jabatan_pegawai,
        ]);

        return response([
            'message' => 'Add Employee Success',
            'data' => $pegawai
        ], 200); 
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::where('id_pegawai',$id);

        if(is_null($pegawai)) {
            return response([
                'message' => 'Employee Not Found',
                'data' => null
            ], 404);
        }

        if($pegawai->delete()) {
            return response([
                'message' => 'Delete Employee Success',
                'data' => $pegawai
            ], 200); 
        }

        return response([
            'message' => 'Delete Employee Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::where('id_pegawai',$id)->first();
        if(is_null($pegawai)) {
            return response([
                'message' => 'Employee Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'nama_pegawai' => 'required|string',
            'alamat_pegawai' => 'required|string',
            'tanggal_lahir_pegawai' => 'required',
            'jenis_kelamin_pegawai' => 'required',
            'email_pegawai' => ['required', Rule::unique('customers','email_customer'), Rule::unique('pegawais','email_pegawai')->ignore($pegawai), Rule::unique('drivers','email_driver')],
            // 'password_pegawai' => 'nullable|string',
            'no_telp_pegawai' => 'required|string',
            'foto_pegawai' => 'nullable|image|mimes:jpeg,jpg,png',
            'jabatan_pegawai' => 'nullable|string',
        ]);

        if($request->foto_pegawai != null)
            $photoEmployee = $request->foto_pegawai->store('foto_pegawai',['disk' => 'public']);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $pegawai->nama_pegawai=$updateData['nama_pegawai'];
        $pegawai->alamat_pegawai=$updateData['alamat_pegawai'];
        $pegawai->tanggal_lahir_pegawai=$updateData['tanggal_lahir_pegawai'];
        $pegawai->jenis_kelamin_pegawai=$updateData['jenis_kelamin_pegawai'];
        $pegawai->email_pegawai=$updateData['email_pegawai'];
        // $pegawai->password_pegawai=$updateData['password_pegawai'];
        $pegawai->no_telp_pegawai=$updateData['no_telp_pegawai'];
        if($request->foto_pegawai != null)
            $pegawai->foto_pegawai=$photoEmployee;
        if($request->jabatan_pegawai != null)
            $pegawai->jabatan_pegawai=$updateData['jabatan_pegawai'];

        if($pegawai->save()) {
            return response([
                'message' => 'Update Employee Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Update Employee Failed',
            'data' => null,
        ], 400);
    }

    
    public function updatePassword(Request $request, $id)
    {
        $pegawai=Pegawai::where('id_pegawai',$id)->first();
        if(is_null($pegawai)) {
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'current_password' => 'required|string',
            'new_password' => 'required|string',
            'repeat_password' => 'required|string|same:new_password',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        if(Hash::check($request->current_password, $pegawai->password_pegawai)){
                $pegawai->password_pegawai=bcrypt($updateData['new_password']);
        }
        else {
            return response([
                'message' => 'Password lama tidak sesuai',
                'data' => null
                ]);
        }

        if($pegawai->save()) {
            return response([
                'message' => 'Update Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Update Pegawai Failed',
            'data' => null,
        ], 400);
    }
}
