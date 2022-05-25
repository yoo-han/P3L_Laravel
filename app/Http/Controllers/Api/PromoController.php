<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Promo;
use App\Models\Customer;
use Carbon\Carbon;
use DateTime;

class PromoController extends Controller
{
    public function index()
    {
        $promo = Promo::all();

        if (count($promo) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $promo
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function show($id)
    {
        $promo = Promo::where('id_promo',$id)->first();

        if(!is_null($promo)) {
            return response([
                'message' => 'Retrieve Promo Success',
                'data' => $promo
            ], 200); 
        }

        return response([
            'message' => 'Promo Not Found',
            'data' => null
        ], 404);
    }

    public function showByIdCustomer(Request $request)
    {
        $customer = Customer::where('id_customer', $request->id_customer)->first();
        $getTanggalLahir = new DateTime($customer->tanggal_lahir_customer);
        $interval = Carbon::now()->diff($getTanggalLahir);

        $date = Carbon::now();

        $kodepromo = array();

        if($interval->y >= 17 && $interval->y <= 22 && $customer->ktp_customer != null){
            $kodepromo[] = 'MHS';
        }

        if(Promo::where('kode_promo', 'MDK')->where('status', 'active')){
            $kodepromo[] = 'MDK';
        }

        if($interval->d == 0 && $date->format('m') == Carbon::parse($getTanggalLahir)->format('m')){
            $kodepromo[] = 'BDAY';
        }

        if($date->format('l') == 'Saturday' || $date->format('l') == 'Sunday'){
            $kodepromo[] = 'WKN';
        }

       $promo = Promo::where('status_promo', 'Aktif')->whereIn('kode_promo',$kodepromo)->get();
        if(!is_null($customer)){
            return response([
                'message' => 'Retrieve Promo Success',
                'data' => $promo
            ], 200);
        }

        return response([
            'message' => 'Promo Not Found',
            'data' => null
        ], 400);
    }

    public function store(Request $request)
    {
        $storeData=$request->all();
        $validate=Validator::make($storeData, [
            'kode_promo' => 'required|string',
            'jenis_promo' => 'required|string',
            'potongan_promo' => 'required',
            'keterangan_promo' => 'required|string',
        ]); 
        
        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 

        $promo=Promo::create($storeData);
        return response([
            'message' => 'Add Promo Success',
            'data' => $promo
        ], 200); 
    }

    public function destroy($id)
    {
        $promo = Promo::where('id_promo',$id);

        if(is_null($promo)) {
            return response([
                'message' => 'Promo Not Found',
                'data' => null
            ], 404);
        }

        if($promo->delete()) {
            return response([
                'message' => 'Delete Promo Success',
                'data' => $promo
            ], 200); 
        }

        return response([
            'message' => 'Delete Promo Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $promo = Promo::where('id_promo',$id)->first();
        if(is_null($promo)) {
            return response([
                'message' => 'Promo Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'kode_promo' => 'required|string',
            'jenis_promo' => 'required|string',
            'potongan_promo' => 'required',
            'keterangan_promo' => 'required|string',
            'status_promo' => 'required|string',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $promo->kode_promo=$updateData['kode_promo'];
        $promo->jenis_promo=$updateData['jenis_promo'];
        $promo->potongan_promo=$updateData['potongan_promo'];
        $promo->keterangan_promo=$updateData['keterangan_promo'];
        $promo->status_promo=$updateData['status_promo'];

        if($promo->save()) {
            return response([
                'message' => 'Update Promo Success',
                'data' => $promo
            ], 200);
        }

        return response([
            'message' => 'Update Promo Failed',
            'data' => null,
        ], 400);
    }
}
