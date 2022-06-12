<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\ReservasiMobil;
use App\Models\Mobil;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservasiMobilController extends Controller
{
    public function index()
    {
        $reservation = ReservasiMobil::with('getMobil','getDriver','getPegawai','getPromo','getCustomer')->get();

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
        $reservation = ReservasiMobil::with('getMobil','getDriver','getPegawai','getPromo','getCustomer')->where('id_reservasi',$id)->first();

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

    public function showCustomer($id_customer)
    {
        $reservation = ReservasiMobil::with('getMobil','getDriver','getPegawai','getPromo','getCustomer')->where('id_customer',$id_customer)->get();

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

    public function showDriver($id_driver)
    {
        $reservation = ReservasiMobil::with('getMobil','getDriver','getPegawai','getPromo','getCustomer')->where('id_driver',$id_driver)->get();

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
            'id_promo' => 'nullable',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'jenis_reservasi' => 'required|string',
            'no_sim' => 'nullable|string',
            'tarif_driver' => 'nullable',
            'metode_pembayaran' => 'required',
            'total_pembayaran' => 'required',
        ]); 

        $database = DB::table('reservasi_mobils')->count();
        if($database == 0){
            if($request->jenis_reservasi == 'Peminjaman Mobil dan Driver')
                $id_reservasi = 'TRN'.date('dmy').'01-'.sprintf('%03d',1);
            else
                $id_reservasi = 'TRN'.date('dmy').'00-'.sprintf('%03d',1);
        }else{
            $get_data = ReservasiMobil::select(DB::raw('GROUP_CONCAT(distinct SUBSTRING(id_reservasi,-3)) as new_id_reservasi'))->get();
            foreach($get_data as $new_value){
                $current = substr($new_value['new_id_reservasi'], -3);
            }
            $increment = $current + 1;

            if($request->jenis_reservasi == 'Peminjaman Mobil dan Driver'){
                $id_reservasi = 'TRN'.date('ymd').'01-'.sprintf('%03d', $increment);
            }
            else{
                $id_reservasi = 'TRN'.date('ymd').'00-'.sprintf('%03d', $increment);
            }
        }
        
        $transactionDate = Carbon::now();
        
        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 

        $reservation=ReservasiMobil::create([
            'id_reservasi' => $id_reservasi,
            'id_customer' => $request->id_customer,
            'id_mobil' => $request->id_mobil, 
            'id_promo' => $request->id_promo,
            'tanggal_transaksi' => $transactionDate,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jenis_reservasi' => $request->jenis_reservasi,
            'no_sim' => $request->no_sim,
            'tarif_driver' => $request->tarif_driver,
            'metode_pembayaran' => $request->metode_pembayaran,
            'total_pembayaran' => $request->total_pembayaran,
        ]);

        $car = Mobil::where('id_mobil', $request->id_mobil)->first();
        $car->status_mobil='Sedang Dipinjam';
        $car->total_peminjaman += 1; 
        $car->save();

        return response([
            'message' => 'Add Reservation Success',
            'data' => $reservation
        ], 200); 
    }

    public function destroy($id)
    {
        $reservation = ReservasiMobil::where('id_reservasi',$id)->first();
        
        $car = Mobil::where('id_mobil', $reservation->id_mobil)->first();
        $car->status_mobil='Tersedia';
        $car->save();

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
            'id_driver' => 'nullable',
            'id_pegawai' => 'nullable',
            'tanggal_mulai' => 'nullable',
            'tanggal_selesai' => 'nullable',
            'tarif_driver' => 'nullable',
            'metode_pembayaran' => 'nullable',
            'bukti_transfer' => 'nullable|image|mimes:jpeg,jpg,png',
            'total_pembayaran' => 'nullable',
            'tanggal_kembali' => 'nullable',
            'denda' => 'nullable',
            'rating_driver' => 'nullable',
            'status_reservasi' => 'nullable',
        ]);

        if($request->bukti_transfer != null)
            $transfer = $request->bukti_transfer->store('bukti_transfer',['disk' => 'public']);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        if($request->id_driver != null){
            $reservation->id_driver=$updateData['id_driver'];
            $driver = Driver::where('id_driver', $updateData['id_driver'])->first();
            $driver->status_driver='Not Available';
            $driver->save();
        }
        if($request->id_pegawai != null)
            $reservation->id_pegawai=$updateData['id_pegawai'];
        if($request->tanggal_mulai != null)
            $reservation->tanggal_mulai=$updateData['tanggal_mulai'];
        if($request->tanggal_selesai != null)
            $reservation->tanggal_selesai=$updateData['tanggal_selesai'];
        if($request->tarif_driver != null)
            $reservation->tarif_driver=$updateData['tarif_driver'];
        if($request->metode_pembayaran != null)
            $reservation->metode_pembayaran=$updateData['metode_pembayaran'];
        if($request->bukti_transfer != null)
            $reservation->bukti_transfer=$transfer;
        if($request->total_pembayaran != null)
            $reservation->total_pembayaran=$updateData['total_pembayaran'];
        if($request->tanggal_kembali != null)
            $reservation->tanggal_kembali=$updateData['tanggal_kembali'];
        if($request->denda != null){
            if($reservation->denda != null){
                $temp=$reservation->total_pembayaran - $reservation->denda;
            }
            else {
                $temp=$reservation->total_pembayaran;
            }
            $reservation->denda=$updateData['denda'];
            $reservation->total_pembayaran=$temp+$reservation->denda;
            
        }
        if($request->rating_driver != null){
            $reservation->rating_driver=$updateData['rating_driver'];

            $driver = Driver::where('id_driver', $reservation->id_driver)->first();
            $temp = $driver->rerata_rating * $driver->banyak_rating;
            $driver->banyak_rating+=1;
            $driver->rerata_rating = ($temp + $reservation->rating_driver) / $driver->banyak_rating;
            $driver->save();
        }
        if($request->status_reservasi != null){
            $reservation->status_reservasi=$updateData['status_reservasi'];
            
            if($updateData['status_reservasi'] == 'Belum Bayar Belum Verifikasi'){
                if($reservation->jenis_reservasi == 'Penyewaan Mobil dan Driver'){
                    $driver = Driver::where('id_driver', $reservation->id_driver)->first();
                    $driver->status_driver='Available';
                    $driver->save();
                }
                $car = Mobil::where('id_mobil', $reservation->id_mobil)->first();
                    $car->status_mobil='Tersedia';
                    $car->save();
            } 
            
        }

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
