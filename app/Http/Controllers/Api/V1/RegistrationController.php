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
            "email" => "required|email:rfc,dns|unique:verifications,email",
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
            "phone_number.unique" => "Email sudah terdaftar",
            "address.required" => "Alamat perusahaan harus diisi",
            "transaction_type.required" => "Jenis transaksi harus diisi",
            "transaction_type.uuid" => "Jenis transaksi tidak valid",
            "transaction_sub_type.required" => "Jenis sub transaksi harus diisi",
            "transaction_sub_type.uuid" => "Jenis sub transaksi tidak valid",
            "contact_email_name.*.required" => "Nama kontak email harus diisi",
            "contact_email_address.*.required" => "Email harus diisi",
            "contact_phone_name.*.required" => "Nama kontak telepon harus diisi",
            "contact_phone_number.*.required" => "Nomor telepon harus diisi",
            "website.active_url" => "Link website tidak aktif",
        ]);

        $validator->sometimes("website", "active_url", function () use ($request) {
            return $request->filled("website");
        });

        foreach ($request->contact_email_name as $iContactEmail => $name) {
            $validator->sometimes("contact_email_name.{$iContactEmail}", ["required"], function () use ($request, $iContactEmail) {
                return $request->filled("contact_email_address.{$iContactEmail}");
            });
        }

        foreach ($request->contact_email_address as $iContactEmail => $name) {
            $validator->sometimes("contact_email_address.{$iContactEmail}", ["required"], function () use ($request, $iContactEmail) {
                return $request->filled("contact_email_name.{$iContactEmail}");
            });
        }

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                403
            );
        }

        $verification = Verification::create([
            "transaction_sub_type_id" => $request->transaction_sub_type,
            "name" => $request->name,
            "pic_name" => $request->pic_name,
            "email" => $request->email,
            "phone_number" => $request->phone_number,
            "address" => $request->address,
            "website" => $request->website,
        ]);

        foreach ($request->contact_email_address as $iEmail => $email) {
            if (!$request->filled("contact_email_name.{$iEmail}") && !$request->filled("contact_email_address.{$iEmail}")) {
                continue;
            }

            $verification->emails()->create([
                "name" => $request->contact_email_name[$iEmail],
                "address" => $request->contact_email_address[$iEmail],
            ]);
        }

        foreach ($request->contact_phone_number as $iEmail => $email) {
            if (!$request->filled("contact_phone_name.{$iEmail}") && !$request->filled("contact_phone_number.{$iEmail}")) {
                continue;
            }

            $verification->emails()->create([
                "name" => $request->contact_phone_name[$iEmail],
                "address" => $request->contact_phone_number[$iEmail],
            ]);
        }

        return response()->json([
            "message" => "Data berhasil ditambahkan",
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
