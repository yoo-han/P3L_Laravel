<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();

        if (count($customers) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $customers
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function show($id)
    {
        $customer = Customer::where('id_customer',$id)->first();

        if(!is_null($customer)) {
            return response([
                'message' => 'Retrieve Customer Success',
                'data' => $customer
            ], 200); 
        }

        return response([
            'message' => 'Customer Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $get_data = Customer::orderBy('id_customer','DESC')->first();
        if(is_null($get_data)){
            $id_customer = 'CUS'.date('ymd').'-'.sprintf('%03d',1);
        }else{
            $current = explode('-',$get_data->id_customer)[1];
            $increment = $current+1;
            $id_customer = 'CUS'.date('ymd').'-'.sprintf('%03d',$increment);
        }

        $storeData=$request->all();
        $validate=Validator::make($storeData, [
            'nama_customer' => 'required|string',
            'alamat_customer' => 'required|string',
            'tanggal_lahir_customer' => 'required',
            'jenis_kelamin_customer' => 'required',
            'email_customer' => 'required|string|email|unique:customers,email_customer|unique:pegawais,email_pegawai|unique:drivers,email_driver',
            'no_telp_customer' => 'required|string',
            'ktp_customer' => 'required|image|mimes:jpeg,jpg,png',
        ]); 

        $birthdate = date('dmY', strtotime($request->tanggal_lahir_customer));
        $passwordCustomer = bcrypt($birthdate);
        $ktpCustomer = $request->ktp_customer->store('data_customer',['disk' => 'public']);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 

        $customer=Customer::create([
            'id_customer' => $id_customer,
            'nama_customer' => $request->nama_customer,
            'alamat_customer' => $request->alamat_customer,
            'tanggal_lahir_customer' => $request->tanggal_lahir_customer,
            'jenis_kelamin_customer' => $request->jenis_kelamin_customer,
            'email_customer' => $request->email_customer,
            'password_customer' => $passwordCustomer,
            'no_telp_customer' => $request->no_telp_customer,
            'ktp_customer' => $ktpCustomer
        ]);

        return response([
            'message' => 'Add Customer Success',
            'data' => $customer
        ], 200); 
    }

    public function destroy($id)
    {
        $customer = Customer::where('id_customer',$id);

        if(is_null($customer)) {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404);
        }

        if($customer->delete()) {
            return response([
                'message' => 'Delete Customer Success',
                'data' => $customer
            ], 200); 
        }

        return response([
            'message' => 'Delete Customer Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $customer=Customer::where('id_customer',$id)->first();
        if(is_null($customer)) {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'nama_customer' => 'required|string',
            'alamat_customer' => 'required|string',
            'tanggal_lahir_customer' => 'required',
            'jenis_kelamin_customer' => 'required',
            'email_customer' => ['required', Rule::unique('customers','email_customer')->ignore($customer), Rule::unique('pegawais','email_pegawai'), Rule::unique('drivers','email_driver')],
            'no_telp_customer' => 'required|string',
            // 'ktp_customer' => 'nullable|image|mimes:jpeg,jpg,png',
            'status_customer' => 'nullable|string',
        ]);

        // $ktpCustomer = $request->ktp_customer->store('data_customer',['disk' => 'public']);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $customer->nama_customer=$updateData['nama_customer'];
        $customer->alamat_customer=$updateData['alamat_customer'];
        $customer->tanggal_lahir_customer=$updateData['tanggal_lahir_customer'];
        $customer->jenis_kelamin_customer=$updateData['jenis_kelamin_customer'];
        $customer->email_customer=$updateData['email_customer'];
        $customer->no_telp_customer=$updateData['no_telp_customer'];
        if($request->status_customer != null)
            $customer->status_customer=$updateData['status_customer'];
        // $customer->ktp_customer = $ktpCustomer;

        if($customer->save()) {
            return response([
                'message' => 'Update Customer Success',
                'data' => $customer
            ], 200);
        }

        return response([
            'message' => 'Update Customer Failed',
            'data' => null,
        ], 400);
    }

    public function updatePassword(Request $request, $id)
    {
        $customer=Customer::where('id_customer',$id)->first();
        if(is_null($customer)) {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'current_password' => 'required|string',
            'new_password' => 'required|string',
            'repeat_password' => 'required|string|same:new_password',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        if(Hash::check($request->current_password, $customer->password_customer)){
                $customer->password_customer=bcrypt($updateData['new_password']);
        }
        else {
            return response([
                'message' => 'Password lama tidak sesuai',
                'data' => null
                ]);
        }

        if($customer->save()) {
            return response([
                'message' => 'Update Customer Success',
                'data' => $customer
            ], 200);
        }

        return response([
            'message' => 'Update Customer Failed',
            'data' => null,
        ], 400);
    }

    public function updateRating(Request $request, $id)
    {
        $customer=Customer::where('id_customer',$id)->first();
        if(is_null($customer)) {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'rating_ajr' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $customer->rating_ajr=$updateData['rating_ajr'];

        if($customer->save()) {
            return response([
                'message' => 'Update Customer Success',
                'data' => $customer
            ], 200);
        }

        return response([
            'message' => 'Update Customer Failed',
            'data' => null,
        ], 400);
    }
}
