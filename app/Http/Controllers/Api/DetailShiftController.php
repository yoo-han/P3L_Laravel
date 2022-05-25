<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\DetailShift;

class DetailShiftController extends Controller
{
    public function index()
    {
        $jadwal = DetailShift::with('getPegawai')->with('getShift')->get();

        if (count($jadwal) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwal
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function show($id)
    {
        $jadwal = DetailShift::with('getPegawai')->with('getShift')->where('id_detail_shift',$id)->first();

        if(!is_null($jadwal)) {
            return response([
                'message' => 'Retrieve Schedule Success',
                'data' => $jadwal
            ], 200); 
        }

        return response([
            'message' => 'Schedule Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData=$request->all();
        $validate=Validator::make($storeData, [
            'id_pegawai' => 'required',
            'id_shift' => 'required',
            'hari' => 'required|string',
        ]); 

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 

        $countShift = DetailShift::select('id_pegawai')->where('id_pegawai', $request->id_pegawai)->count();

        if($countShift == 6){
            return response([
                'message' => 'Shift untuk pegawai telah mencapai maksimal 6 shift/minggu',
            ], 400);
        }

        $cekHari = DetailShift::select('id_pegawai')->addSelect('id_shift')->addSelect('hari')->where('id_pegawai', $request->id_pegawai)->where('id_shift', $request->id_shift)->where('hari', $request->hari)->first();

        if($cekHari){
            return response([
                'message' => 'Pegawai telah memiliki shift pada hari ini',
            ], 400);
        }

        $jadwal=DetailShift::create($storeData);
        return response([
            'message' => 'Add Schedule Success',
            'data' => $jadwal
        ], 200); 
    }

    public function destroy($id)
    {
        $jadwal = DetailShift::where('id_detail_shift',$id);

        if(is_null($jadwal)) {
            return response([
                'message' => 'Schedule Not Found',
                'data' => null
            ], 404);
        }

        if($jadwal->delete()) {
            return response([
                'message' => 'Delete Schedule Success',
                'data' => $jadwal
            ], 200); 
        }

        return response([
            'message' => 'Delete Schedule Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $jadwal = DetailShift::where('id_detail_shift',$id)->first();
        if(is_null($jadwal)) {
            return response([
                'message' => 'Schedule Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'id_shift' => 'required',
            'hari' => 'required|string',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $cekHari = DetailShift::select('id_pegawai')->addSelect('id_shift')->addSelect('hari')->where('id_pegawai', $request->id_pegawai)->where('id_shift', $request->id_shift)->where('hari', $request->hari)->first();

        if($cekHari){
            return response([
                'message' => 'Pegawai telah memiliki shift pada hari ini',
            ], 400);
        }

        $jadwal->id_shift=$updateData['id_shift'];
        $jadwal->hari=$updateData['hari'];

        if($jadwal->save()) {
            return response([
                'message' => 'Update Schedule Success',
                'data' => $jadwal
            ], 200);
        }

        return response([
            'message' => 'Update Schedule Failed',
            'data' => null,
        ], 400);
    }
}
