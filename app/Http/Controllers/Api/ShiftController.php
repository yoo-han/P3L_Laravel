<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Shift;

class ShiftController extends Controller
{
    public function index()
    {
        $shift = Shift::all();

        if (count($shift) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $shift
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function show($id)
    {
        $shift = Shift::where('id_shift',$id)->first();

        if(!is_null($shift)) {
            return response([
                'message' => 'Retrieve Shift Success',
                'data' => $shift
            ], 200); 
        }

        return response([
            'message' => 'Shift Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData=$request->all();
        $validate=Validator::make($storeData, [
            'nama_shift' => 'required',
            'jam_kerja_mulai' => 'required',
            'jam_kerja_selesai' => 'required',
        ]); 
        
        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 

        $shift=Shift::create($storeData);
        return response([
            'message' => 'Add Shift Success',
            'data' => $shift
        ], 200); 
    }

    public function destroy($id)
    {
        $shift = Shift::where('id_shift',$id);

        if(is_null($shift)) {
            return response([
                'message' => 'Shift Not Found',
                'data' => null
            ], 404);
        }

        if($shift->delete()) {
            return response([
                'message' => 'Delete Shift Success',
                'data' => $shift
            ], 200); 
        }

        return response([
            'message' => 'Delete Shift Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::where('id_shift',$id)->first();
        if(is_null($shift)) {
            return response([
                'message' => 'Shift Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'nama_shift' => 'required',
            'jam_kerja_mulai' => 'required',
            'jam_kerja_selesai' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $shift->nama_shift=$updateData['nama_shift'];
        $shift->jam_kerja_mulai=$updateData['jam_kerja_mulai'];
        $shift->jam_kerja_selesai=$updateData['jam_kerja_selesai'];

        if($shift->save()) {
            return response([
                'message' => 'Update Shift Success',
                'data' => $shift
            ], 200);
        }

        return response([
            'message' => 'Update Shift Failed',
            'data' => null,
        ], 400);
    }
}
