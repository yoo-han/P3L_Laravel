<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Driver;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::all();

        if (count($drivers) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $drivers
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function show($id)
    {
        $driver = Driver::where('id_driver',$id)->first();

        if(!is_null($driver)) {
            return response([
                'message' => 'Retrieve Driver Success',
                'data' => $driver
            ], 200); 
        }

        return response([
            'message' => 'Driver Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $get_data = Driver::orderBy('id_driver','DESC')->first();
        if(is_null($get_data)){
            $id_driver = 'DRV'.date('dmy').'-'.sprintf('%03d',1);
        }else{
            $current = explode('-',$get_data->id_driver)[1];
            $increment = $current+1;
            $id_driver = 'DRV'.date('dmy').'-'.sprintf('%03d',$increment);
        }

        $storeData=$request->all();
        $validate=Validator::make($storeData, [
            'nama_driver' => 'required|string',
            'alamat_driver' => 'required|string',
            'tanggal_lahir_driver' => 'required',
            'jenis_kelamin_driver' => 'required',
            'email_driver' => 'required|string|email|unique:customers,email_customer|unique:pegawais,email_pegawai|unique:drivers,email_driver',
            'no_telp_driver' => 'required|string',
            'bahasa'  => 'required',
            'foto_driver' => 'required|image|mimes:jpeg,jpg,png',
            'sim_driver' => 'required|image|mimes:jpeg,jpg,png',
            'surat_bebas_napza' => 'required|image|mimes:jpeg,jpg,png',
            'surat_kesehatan_jasmani' => 'required|image|mimes:jpeg,jpg,png',
            'surat_kesehatan_jiwa' => 'required|image|mimes:jpeg,jpg,png',
            'skck' => 'required|image|mimes:jpeg,jpg,png',
        ]); 

        $birthdate = date('dmY', strtotime($request->tanggal_lahir_driver));
        $passwordDriver = bcrypt($birthdate);
        $fotoDriver = $request->foto_driver->store('data_driver',['disk' => 'public']);
        $simDriver = $request->sim_driver->store('data_driver',['disk' => 'public']);
        $sbnDriver = $request->surat_bebas_napza->store('data_driver',['disk' => 'public']);
        $skjaDriver = $request->surat_kesehatan_jasmani->store('data_driver',['disk' => 'public']);
        $skjiDriver = $request->surat_kesehatan_jiwa->store('data_driver',['disk' => 'public']);
        $skckDriver = $request->skck->store('data_driver',['disk' => 'public']);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 

        $driver=Driver::create([
            'id_driver' => $id_driver,
            'nama_driver' => $request->nama_driver,
            'alamat_driver' => $request->alamat_driver,
            'tanggal_lahir_driver' => $request->tanggal_lahir_driver,
            'jenis_kelamin_driver' => $request->jenis_kelamin_driver,
            'email_driver' => $request->email_driver,
            'password_driver' => $passwordDriver,
            'no_telp_driver' => $request->no_telp_driver,
            'bahasa'  => $request->bahasa,
            'foto_driver' => $fotoDriver,
            'sim_driver' => $simDriver,
            'surat_bebas_napza' => $sbnDriver,
            'surat_kesehatan_jasmani' => $skjaDriver,
            'surat_kesehatan_jiwa' => $skjiDriver,
            'skck' => $skckDriver,
        ]);

        return response([
            'message' => 'Add Driver Success',
            'data' => $driver
        ], 200); 
    }

    public function destroy($id)
    {
        $driver = Driver::where('id_driver',$id);

        if(is_null($driver)) {
            return response([
                'message' => 'Driver Not Found',
                'data' => null
            ], 404);
        }

        if($driver->delete()) {
            return response([
                'message' => 'Delete Driver Success',
                'data' => $driver
            ], 200); 
        }

        return response([
            'message' => 'Delete Driver Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $driver=Driver::where('id_driver',$id)->first();
        if(is_null($driver)) {
            return response([
                'message' => 'Driver Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'nama_driver' => 'required|string',
            'alamat_driver' => 'required|string',
            'tanggal_lahir_driver' => 'required',
            'jenis_kelamin_driver' => 'required',
            'email_driver' => ['required', Rule::unique('customers','email_customer'), Rule::unique('pegawais','email_pegawai'), Rule::unique('drivers','email_driver')->ignore($driver)],
            // 'password_driver' => 'required|string',
            'no_telp_driver' => 'required|string',
            'bahasa'  => 'required',
            'foto_driver' => 'nullable|image|mimes:jpeg,jpg,png',
            'sim_driver' => 'nullable|image|mimes:jpeg,jpg,png',
            'surat_bebas_napza' => 'nullable|image|mimes:jpeg,jpg,png',
            'surat_kesehatan_jasmani' => 'nullable|image|mimes:jpeg,jpg,png',
            'surat_kesehatan_jiwa' => 'nullable|image|mimes:jpeg,jpg,png',
            'skck' => 'nullable|image|mimes:jpeg,jpg,png',
            'status_driver' => 'required|string',
            'rerata_rating' => 'nullable',
            'banyak_rating' => 'nullable',
        ]);

        if($request->foto_driver != null)
        $fotoDriver = $request->foto_driver->store('data_driver',['disk' => 'public']);
        if($request->sim_driver != null)
        $simDriver = $request->sim_driver->store('data_driver',['disk' => 'public']);
        if($request->surat_bebas_napza != null)
        $sbnDriver = $request->surat_bebas_napza->store('data_driver',['disk' => 'public']);
        if($request->surat_kesehatan_jasmani != null)
        $skjaDriver = $request->surat_kesehatan_jasmani->store('data_driver',['disk' => 'public']);
        if($request->surat_kesehatan_jiwa != null)
        $skjiDriver = $request->surat_kesehatan_jiwa->store('data_driver',['disk' => 'public']);
        if($request->skck != null)
        $skckDriver = $request->skck->store('data_driver',['disk' => 'public']);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $driver->nama_driver=$updateData['nama_driver'];
        $driver->alamat_driver=$updateData['alamat_driver'];
        $driver->tanggal_lahir_driver=$updateData['tanggal_lahir_driver'];
        $driver->jenis_kelamin_driver=$updateData['jenis_kelamin_driver'];
        $driver->email_driver=$updateData['email_driver'];
        // $driver->password_driver=$updateData['password_driver'];
        $driver->no_telp_driver=$updateData['no_telp_driver'];
        $driver->bahasa=$updateData['bahasa'];
        if($request->foto_driver != null)
        $driver->foto_driver = $fotoDriver;
        if($request->sim_driver != null)
        $driver->sim_driver=$simDriver;
        if($request->surat_bebas_napza != null)
        $driver->surat_bebas_napza=$sbnDriver;
        if($request->surat_kesehatan_jasmani != null)
        $driver->surat_kesehatan_jasmani=$skjaDriver;
        if($request->surat_kesehatan_jiwa != null)
        $driver->surat_kesehatan_jiwa=$skjiDriver;
        if($request->skck != null)
        $driver->skck=$skckDriver;
        $driver->status_driver=$updateData['status_driver'];
        if($request->rerata_rating != null)
        $driver->rerata_rating=$updateData['rerata_rating'];
        if($request->banyak_rating != null)
        $driver->banyak_rating=$updateData['banyak_rating'];

        if($driver->save()) {
            return response([
                'message' => 'Update Driver Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Update Driver Failed',
            'data' => null,
        ], 400);
    }
}
