<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "pic_name" => "required",
            "email" => "required|email:rfc,dns",
            "phone_number" => "required|digits_between:10,13|unique:verifications,phone_number",
            "address" => "required",
            "transaction_type" => "required|uuid",
            "transaction_sub_type" => ["uuid", Rule::requiredIf($request->filled('transaction_type'))],
        ], [
            "name.required" => "Nama perusahaan harus diisi",
            "pic_name.required" => "Nama PIC harus diisi",
            "email.required" => "Email harus diisi",
            "email.email" => "Alamat email tidak valid",
            "phone_number.required" => "Nomor telepon harus diisi",
            "phone_number.digits_between" => "Nomor telepon minimal :min maksimal :max",
            "phone_number.unique" => "Nomor telepon sudah terdaftar",
            "address.required" => "Alamat perusahaan harus diisi",
            "transaction_type.required" => "Jenis transaksi harus diisi",
            "transaction_type.uuid" => "Jenis transaksi tidak valid",
            "transaction_sub_type.required" => "Jenis sub transaksi harus diisi",
            "transaction_sub_type.uuid" => "Jenis sub transaksi tidak valid",
            "website.active_url" => "Link website tidak aktif",
        ]);

        $validator->sometimes("website", "active_url", function () use ($request) {
            return $request->filled("website");
        });

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                403
            );
        }

        // name: 'sadasdsadsa',
        // pic_name: 'Barata',
        // email: 'barata@gmail.com',
        // phone_number: '089643418173',
        // website: '',
        // address: 'kp',
        // transaction_type: '9bfbed38-7459-43f5-8d49-c6291b8efeb4',
        // transaction_sub_type: '9bfbed38-7643-431f-b98c-d25e6c1011bd'

        $Verification = Verification::create([
            "transaction_sub_type_id" => $request->transaction_sub_type,
            "name" => $request->name,
            "pic_name" => $request->pic_name,
            "email" => $request->email,
            "phone_number" => $request->phone_number,
            "address" => $request->address,
            "website" => $request->website,
        ]);

        return response()->json([
            "message" => "Data berhasil ditambahkan",
            "data" => $request->all()
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
