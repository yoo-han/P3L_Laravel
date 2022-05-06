<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Mobil;

class MobilController extends Controller
{
    public function index()
    {
        $cars = Mobil::with('getMitra')->get();

        if (count($cars) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $cars
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function show($id)
    {
        $car = Mobil::with('getMitra')->where('id_mobil',$id)->first();

        if(!is_null($car)) {
            return response([
                'message' => 'Retrieve Car Success',
                'data' => $car
            ], 200); 
        }

        return response([
            'message' => 'Car Not Found',
            'data' => null
        ], 404);
    }

    public function showTersedia()
    {
        $car = Mobil::with('getMitra')->where('status_mobil','Tersedia')->get();

        if(!is_null($car)) {
            return response([
                'message' => 'Retrieve Car Success',
                'data' => $car
            ], 200); 
        }

        return response([
            'message' => 'Car Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData=$request->all();
        $validate=Validator::make($storeData, [
            'id_mitra'  => 'nullable',
            'nama_mobil' => 'required|string',
            'tipe_mobil' => 'required|string',
            'jenis_transmisi' => 'required|string',
            'jenis_bahan_bakar' => 'required|string',
            'volume_bahan_bakar' => 'required',
            'warna_mobil' => 'required|string',
            'kapasitas_penumpang' => 'required',
            'fasilitas' => 'required|string',
            'kategori_aset' => 'required',
            'plat_nomor' => 'required|string|unique:mobils,plat_nomor',
            'nomor_stnk' => 'required|string|unique:mobils,nomor_stnk',
            'harga_sewa' => 'required',
            'foto_mobil' => 'required|image|mimes:jpeg,jpg,png',
            'tanggal_terakhir_servis' => 'required',
        ]); 

        $photoCar = $request->foto_mobil->store('foto_mobil',['disk' => 'public']);
        
        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 

        $car=Mobil::create([
            'id_mitra' => $request->id_mitra,
            'nama_mobil' => $request->nama_mobil,
            'tipe_mobil' => $request->tipe_mobil,
            'jenis_transmisi' => $request->jenis_transmisi,
            'jenis_bahan_bakar' => $request->jenis_bahan_bakar,
            'volume_bahan_bakar' => $request->volume_bahan_bakar,
            'warna_mobil' => $request->warna_mobil,
            'kapasitas_penumpang' => $request->kapasitas_penumpang,
            'fasilitas'  => $request->fasilitas,
            'kategori_aset' => $request->kategori_aset,
            'plat_nomor' => $request->plat_nomor,
            'nomor_stnk' => $request->nomor_stnk,
            'harga_sewa' => $request->harga_sewa,
            'foto_mobil' => $photoCar,
            'tanggal_terakhir_servis' => $request->tanggal_terakhir_servis,
        ]);

        return response([
            'message' => 'Add Car Success',
            'data' => $car
        ], 200); 
    }

    public function destroy($id)
    {
        $car = Mobil::where('id_mobil',$id);

        if(is_null($car)) {
            return response([
                'message' => 'Car Not Found',
                'data' => null
            ], 404);
        }

        if($car->delete()) {
            return response([
                'message' => 'Delete Car Success',
                'data' => $car
            ], 200); 
        }

        return response([
            'message' => 'Delete Car Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $car = Mobil::where('id_mobil',$id)->first();
        if(is_null($car)) {
            return response([
                'message' => 'Car Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'id_mitra'  => 'nullable',
            'nama_mobil' => 'required|string',
            'tipe_mobil' => 'required|string',
            'jenis_transmisi' => 'required|string',
            'jenis_bahan_bakar' => 'required|string',
            'volume_bahan_bakar' => 'required',
            'warna_mobil' => 'required|string',
            'kapasitas_penumpang' => 'required',
            'fasilitas' => 'required|string',
            'kategori_aset' => 'required',
            'plat_nomor' => ['required', Rule::unique('mobils','plat_nomor')->ignore($car)],
            'nomor_stnk' => ['required', Rule::unique('mobils','nomor_stnk')->ignore($car)],
            'harga_sewa' => 'required',
            'foto_mobil' => 'nullable|image|mimes:jpeg,jpg,png',
            'total_peminjaman' => 'required',
            'tanggal_terakhir_servis' => 'required',
            'status_mobil' => 'required',
        ]);

        if($request->foto_mobil != null)
            $photoCar = $request->foto_mobil->store('foto_mobil',['disk' => 'public']);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $car->id_mitra=$updateData['id_mitra'];
        $car->nama_mobil=$updateData['nama_mobil'];
        $car->tipe_mobil=$updateData['tipe_mobil'];
        $car->jenis_transmisi=$updateData['jenis_transmisi'];
        $car->jenis_bahan_bakar=$updateData['jenis_bahan_bakar'];
        $car->volume_bahan_bakar=$updateData['volume_bahan_bakar'];
        $car->warna_mobil=$updateData['warna_mobil'];
        $car->kapasitas_penumpang=$updateData['kapasitas_penumpang'];
        $car->fasilitas=$updateData['fasilitas'];
        $car->kategori_aset=$updateData['kategori_aset'];
        $car->plat_nomor=$updateData['plat_nomor'];
        $car->nomor_stnk=$updateData['nomor_stnk'];
        $car->harga_sewa=$updateData['harga_sewa'];
        if($request->foto_mobil != null){
            $car->foto_mobil=$photoCar;
        }
        $car->total_peminjaman=$updateData['total_peminjaman'];
        $car->tanggal_terakhir_servis=$updateData['tanggal_terakhir_servis'];
        $car->status_mobil=$updateData['status_mobil'];

        if($car->save()) {
            return response([
                'message' => 'Update Car Success',
                'data' => $car
            ], 200);
        }

        return response([
            'message' => 'Update Car Failed',
            'data' => null,
        ], 400);
    }
}
