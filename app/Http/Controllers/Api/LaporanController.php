<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function penyewaanMobil($bulan, $tahun) {

        $data = DB::select("SELECT m.tipe_mobil AS Tipe_Mobil, m.nama_mobil AS Nama_Mobil, COUNT(r.id_mobil) AS Jumlah_Peminjam, SUM(DATEDIFF(r.tanggal_selesai, r.tanggal_mulai) * m.harga_sewa) AS Pendapatan_Mobil
        FROM reservasi_mobils r 
        JOIN mobils m  ON (r.id_mobil = m.id_mobil) 
        WHERE MONTH(r.tanggal_transaksi) = $bulan AND YEAR(r.tanggal_transaksi) = $tahun 
        GROUP BY m.tipe_mobil, m.nama_mobil, r.id_mobil, m.harga_sewa
        ORDER BY SUM(DATEDIFF(r.tanggal_selesai,r.tanggal_mulai)*m.harga_sewa) DESC");

        if(!is_null($data)) {
            return response([
                'message' => 'Retrieve Data Success',
                'data' => $data
            ], 200); 
        }

        return response([
            'message' => 'Data Not Found',
            'data' => null
        ], 404);
    }

    public function pendapatanTransaksi($bulan, $tahun) {

        $data = DB::select("SELECT c.nama_customer AS Nama_Customer, m.nama_mobil AS Nama_Mobil, r.jenis_reservasi AS Jenis_Transaksi, COUNT(r.id_mobil) AS Jumlah_Transaksi, SUM(DATEDIFF(r.tanggal_selesai,r.tanggal_mulai)*m.harga_sewa) AS Pendapatan_Mobil
        FROM reservasi_mobils r 
        JOIN mobils m ON (r.id_mobil = m.id_mobil) 
        JOIN customers c ON (r.id_customer = c.id_customer) 
        WHERE MONTH(r.tanggal_transaksi) = $bulan AND YEAR(r.tanggal_transaksi) = $tahun
        GROUP BY c.nama_customer, m.nama_mobil, r.jenis_reservasi, r.id_mobil");

        if(!is_null($data)) {
            return response([
                'message' => 'Retrieve Data Success',
                'data' => $data
            ], 200); 
        }

        return response([
            'message' => 'Data Not Found',
            'data' => null
        ], 404);
    }

    public function jumlahTransaksiDriver($bulan, $tahun) {

        $data = DB::select("SELECT id_driver AS ID_Driver, nama_driver AS Nama_Driver, COUNT(id_driver) AS Jumlah_Transaksi
        FROM reservasi_mobils 
        JOIN drivers USING (id_driver) 
        WHERE MONTH(tanggal_transaksi) = $bulan AND YEAR(tanggal_transaksi) = $tahun 
        GROUP BY id_driver, nama_driver
        ORDER BY COUNT(id_driver) DESC
        LIMIT 5");

        if(!is_null($data)) {
            return response([
                'message' => 'Retrieve Data Success',
                'data' => $data
            ], 200); 
        }

        return response([
            'message' => 'Data Not Found',
            'data' => null
        ], 404);
    }

    public function performaDriver($bulan, $tahun) {

        $data = DB::select(DB::raw("SELECT id_driver AS ID_Driver, nama_driver AS Nama_Driver, COUNT(id_driver) AS Jumlah_Transaksi, SUM(rating_driver) / COUNT(id_driver) AS Rerata_Rating
        FROM reservasi_mobils 
        JOIN drivers USING (id_driver) 
        WHERE MONTH(tanggal_transaksi) = '$bulan' AND YEAR(tanggal_transaksi) = '$tahun'
        GROUP BY id_driver, nama_driver
        ORDER BY COUNT(id_driver) DESC
        LIMIT 5"));



        if(!is_null($data)) {
            return response([
                'message' => 'Retrieve Data Success',
                'data' => $data
            ], 200); 
        }

        return response([
            'message' => 'Data Not Found',
            'data' => null
        ], 404);
    }

    public function jumlahTransaksiCustomer($bulan, $tahun) {

        $data = DB::select("SELECT nama_customer AS Nama_Customer, COUNT(id_customer) AS Jumlah_Transaksi
        FROM reservasi_mobils 
        JOIN customers USING (id_customer) 
        WHERE MONTH(tanggal_transaksi) = $bulan AND YEAR(tanggal_transaksi) = $tahun
        GROUP BY id_customer, nama_customer
        ORDER BY COUNT(id_customer) DESC
        LIMIT 5 ");

        if(!is_null($data)) {
            return response([
                'message' => 'Retrieve Customer Success',
                'data' => $data
            ], 200); 
        }

        return response([
            'message' => 'Customer Not Found',
            'data' => null
        ], 404);
    }
}
