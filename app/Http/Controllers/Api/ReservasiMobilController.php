<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\ReservasiMobil;
use Illuminate\Support\Facades\DB;

class ReservasiMobilController extends Controller
{
    public function index()
    {
        $reservation = ReservasiMobil::all();

        if (count($reservation) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $reservation
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function show($id)
    {
        $reservation = ReservasiMobil::where('id_reservasi',$id)->first();

        if(!is_null($reservation)) {
            return response([
                'message' => 'Retrieve Reservation Success',
                'data' => $reservation
            ], 200); 
        }

        return response([
            'message' => 'Reservation Not Found',
            'data' => null
        ], 404);
    }

    public function showCustomer($id)
    {
        $reservation = ReservasiMobil::where('id_customer',$id)->get();

        if(!is_null($reservation)) {
            return response([
                'message' => 'Retrieve Reservation Success',
                'data' => $reservation
            ], 200); 
        }

        return response([
            'message' => 'Reservation Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData=$request->all();
        $validate=Validator::make($storeData, [
            'id_customer' => 'required',
            'id_mobil' => 'required', 
            'id_pegawai' => 'required',
            'id_promo' => 'nullable',
            'id_driver' => 'nullable',
            'tanggal_transaksi' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'jenis_reservasi' => 'required|string',
            'no_sim' => 'nullable|string',
            'tarif_driver' => 'nullable',
            'metode_pembayaran' => 'required',
            'total_pembayaran' => 'required',
            'bukti_transfer' => 'required|image|mimes:jpeg,jpg,png',
        ]); 

        $database = DB::table('reservasi_mobils')->count();
        if($database == 0){
            if(!is_null($request->id_driver))
                $id_reservasi = 'TRN'.date('dmy').'01-'.sprintf('%03d',1);
            else
                $id_reservasi = 'TRN'.date('dmy').'00-'.sprintf('%03d',1);
        }else{
            $get_data = ReservasiMobil::select(DB::raw('GROUP_CONCAT(distinct SUBSTRING(id_reservasi,-3)) as new_id_reservasi'))->get();
            foreach($get_data as $new_value){
                $current = substr($new_value['new_id_reservasi'], -3);
            }
            $increment = $current + 1;

            if(!is_null($request->id_driver)){
                $id_reservasi = 'TRN'.date('ymd').'01-'.sprintf('%03d', $increment);
            }
            else{
                $id_reservasi = 'TRN'.date('ymd').'00-'.sprintf('%03d', $increment);
            }
        }
        
        $transfer = $request->bukti_transfer->store('bukti_transfer',['disk' => 'public']);
        
        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 

        $reservation=ReservasiMobil::create([
            'id_reservasi' => $id_reservasi,
            'id_customer' => $request->id_customer,
            'id_mobil' => $request->id_mobil, 
            'id_pegawai' => $request->id_pegawai,
            'id_promo' => $request->id_promo,
            'id_driver' => $request->id_driver,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jenis_reservasi' => $request->jenis_reservasi,
            'no_sim' => $request->no_sim,
            'tarif_driver' => $request->tarif_driver,
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_transfer' => $transfer,
            'total_pembayaran' => $request->total_pembayaran,
            'tanggal_kembali' => $request->tanggal_kembali,
            'denda' => $request->denda,
            'rating_driver' => $request->rating_driver,
        ]);

        return response([
            'message' => 'Add Reservation Success',
            'data' => $reservation
        ], 200); 
    }

    public function destroy($id)
    {
        $reservation = ReservasiMobil::where('id_reservasi',$id);

        if(is_null($reservation)) {
            return response([
                'message' => 'Reservation Not Found',
                'data' => null
            ], 404);
        }

        if($reservation->delete()) {
            return response([
                'message' => 'Delete Reservation Success',
                'data' => $reservation
            ], 200); 
        }

        return response([
            'message' => 'Delete Reservation Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $reservation=ReservasiMobil::where('id_reservasi',$id)->first();
        if(is_null($reservation)) {
            return response([
                'message' => 'Reservation Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'jenis_reservasi' => 'nullable|string',
            'no_sim' => 'nullable|string',
            'tarif_driver' => 'nullable',
            'metode_pembayaran' => 'required',
            'bukti_transfer' => 'nullable|image|mimes:jpeg,jpg,png',
            'total_pembayaran' => 'required',
            'tanggal_kembali' => 'required',
            'denda' => 'nullable',
            'rating_driver' => 'nullable',
            'status_reservasi' => 'required',
        ]);

        $transfer = $request->bukti_transfer->store('bukti_transfer',['disk' => 'public']);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $reservation->tanggal_mulai=$updateData['tanggal_mulai'];
        $reservation->tanggal_selesai=$updateData['tanggal_selesai'];
        $reservation->jenis_reservasi=$updateData['jenis_reservasi'];
        $reservation->no_sim=$updateData['no_sim'];
        $reservation->tarif_driver=$updateData['tarif_driver'];
        $reservation->metode_pembayaran=$updateData['metode_pembayaran'];
        $reservation->bukti_transfer=$transfer;
        $reservation->total_pembayaran=$updateData['total_pembayaran'];
        $reservation->tanggal_kembali=$updateData['tanggal_kembali'];
        $reservation->denda=$updateData['denda'];
        $reservation->rating_driver=$updateData['rating_driver'];
        $reservation->status_reservasi=$updateData['status_reservasi'];

        if($reservation->save()) {
            return response([
                'message' => 'Update Reservation Success',
                'data' => $reservation
            ], 200);
        }

        return response([
            'message' => 'Update Reservation Failed',
            'data' => null,
        ], 400);
    }
}
