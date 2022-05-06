<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\MitraMobil;

class MitraMobilController extends Controller
{
    public function index()
    {
        $mitra = MitraMobil::all();

        if (count($mitra) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mitra
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function show($id)
    {
        $mitra = MitraMobil::where('id_mitra',$id)->first();

        if(!is_null($mitra)) {
            return response([
                'message' => 'Retrieve Mitra Success',
                'data' => $mitra
            ], 200); 
        }

        return response([
            'message' => 'Mitra Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData=$request->all();
        $validate=Validator::make($storeData, [
            'nama_pemilik' => 'required|string',
            'no_ktp_pemilik' => 'required|string',
            'alamat_pemilik' => 'required|string',
            'no_telp_pemilik' => 'required|string',
            'periode_kontrak_mulai' => 'required',
            'periode_kontrak_akhir' => 'required',
        ]); 
        
        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 

        $mitra=MitraMobil::create($storeData);
        return response([
            'message' => 'Add Mitra Success',
            'data' => $mitra
        ], 200); 
    }

    public function destroy($id)
    {
        $mitra = MitraMobil::where('id_mitra',$id);

        if(is_null($mitra)) {
            return response([
                'message' => 'Mitra Not Found',
                'data' => null
            ], 404);
        }

        if($mitra->delete()) {
            return response([
                'message' => 'Delete Mitra Success',
                'data' => $mitra
            ], 200); 
        }

        return response([
            'message' => 'Delete Mitra Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $mitra = MitraMobil::where('id_mitra',$id)->first();
        if(is_null($mitra)) {
            return response([
                'message' => 'Mitra Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'nama_pemilik' => 'required|string',
            'no_ktp_pemilik' => 'required|string',
            'alamat_pemilik' => 'required|string',
            'no_telp_pemilik' => 'required|string',
            'periode_kontrak_mulai' => 'required',
            'periode_kontrak_akhir' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $mitra->nama_pemilik=$updateData['nama_pemilik'];
        $mitra->no_ktp_pemilik=$updateData['no_ktp_pemilik'];
        $mitra->alamat_pemilik=$updateData['alamat_pemilik'];
        $mitra->no_telp_pemilik=$updateData['no_telp_pemilik'];
        $mitra->periode_kontrak_mulai=$updateData['periode_kontrak_mulai'];
        $mitra->periode_kontrak_akhir=$updateData['periode_kontrak_akhir'];

        if($mitra->save()) {
            return response([
                'message' => 'Update Mitra Success',
                'data' => $mitra
            ], 200);
        }

        return response([
            'message' => 'Update Mitra Failed',
            'data' => null,
        ], 400);
    }
}
