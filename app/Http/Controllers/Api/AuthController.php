<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Pegawai;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]); //membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->error()], 400); //return error validasi input

        if(Customer::where('email_customer', $request->email)->first()){
            if(Customer::where('email_customer', $request->email)->where('status_customer','Unverified')->first()){
                return response([
                    'message' => 'Data belum diverifikasi',
                    'data' => null
                ], 404);
            }
            else{
                $loginCustomer = Customer::where('email_customer', $request->email)->first();
                if(Hash::check($request->password, $loginCustomer->password_customer)){
                    return response([
                        'message' => 'Berhasil Login',
                        'data' => $loginCustomer
                    ]);
                }
                // else {
                //     return response([
                //         'message' => 'Password anda tidak sama',
                //         'data' => null
                //         ]);
                // }
            }
        }
        else if(Pegawai::where('email_pegawai','=',$loginData['email'])->first()){
            $loginPegawai = Pegawai::where('email_pegawai', $request->email)->first();
            if(Hash::check($request->password, $loginPegawai->password_pegawai)){
                return response([
                    'message' => 'Berhasil Login',
                    'data' => $loginPegawai
                ]);
            }
            else {
                // return response([
                //     'message' => 'Password anda tidak sama',
                //     'data' => null
                //     ]);
            }
        }
        else if(Driver::where('email_driver','=',$loginData['email'])->first()){
            
            if(Driver::where('email_driver', $request->email)->where('status_driver','Unverified')->first()){
                return response([
                    'message' => 'Data belum diverifikasi',
                    'data' => null
                ], 404);
            }
            else{
                $loginDriver = Driver::where('email_driver', $request->email)->first();
                if(Hash::check($request->password, $loginDriver->password_driver)){
                    return response([
                        'message' => 'Berhasil Login',
                        'data' => $loginDriver
                    ]);
                }
            // else {
                // return response([
                //     'message' => 'Password anda tidak sama',
                //     'data' => null
                //     ]);}
            }
        }

        return response([
            'message' => 'Login Gagal. Masukkan Email/Password yang benar!',
            'data' => null
        ], 404);
    }
}