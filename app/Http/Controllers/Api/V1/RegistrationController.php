<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\CustomerRegistratedEvent;
use App\Http\Controllers\Controller;
use App\Mail\VerificationLinkUploadMail;
use App\Models\Customer;
use App\Models\TransactionSubType;
use App\Models\User;
use App\Models\Verification;
use App\Notifications\UserRegistratedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\UrlSigner\Laravel\Facades\UrlSigner;
use Illuminate\Support\Facades\Notification;

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
            "email" => "required|unique:verifications,email|email:rfc,dns",
            "phone_number" => "required|digits_between:10,13|unique:verifications,phone_number",
            "address" => "required",
            "transaction_type" => "required|uuid",
            "transaction_sub_type" => ["uuid", Rule::requiredIf($request->filled('transaction_type'))],
        ], [
            "name.required" => "Nama perusahaan harus diisi",
            "pic_name.required" => "Nama PIC harus diisi",
            "email.required" => "Email harus diisi",
            "email.email" => "Alamat email tidak valid",
            "email.unique" => "Email sudah terdaftar",
            "phone_number.required" => "Nomor telepon harus diisi",
            "phone_number.digits_between" => "Nomor telepon minimal :min maksimal :max",
            "phone_number.unique" => "Nomor telepon sudah terdaftar",
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

        foreach ($request->contact_phone_number as $iPhone => $phone) {
            if (!$request->filled("contact_phone_name.{$iPhone}") && !$request->filled("contact_phone_number.{$iPhone}")) {
                continue;
            }

            $verification->phones()->create([
                "name" => $request->contact_phone_name[$iPhone],
                "number" => $request->contact_phone_number[$iPhone],
            ]);
        }

        foreach (TransactionSubType::where("id", $verification->transaction_sub_type_id)->with(["documentTemplates"])->first()->documentTemplates as $document) {
            $verification->documents()->create([
                "document_id" => $document->id
            ]);
        }

        $url = UrlSigner::sign("http://localhost:3000/registration/{$verification->id}/upload");

        $verification->update([
            "link" => $url
        ]);

        $verification->fresh();

        CustomerRegistratedEvent::dispatch($verification, $verification->link, "Mohon untuk mengisi dokumen-dokumen yang harus dipenuhi.");
        Notification::send(User::where("role", "admin")->get(), new UserRegistratedNotification($verification));

        return response()->json([
            "message" => "Terimakasih telah menggunakan jasa PT. Sinar Lautan Maritim. Mohon untuk memeriksa email untuk lanjut ketahapan berikutnya",
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

    public function verification(Request $request)
    {
        // $valid = UrlSigner::validate("http://localhost:3000/registration/{$request->verification}/upload?expires={$request->expires}&signature={$request->signature}");

        // if (!$valid) {
        //     return response()->json([
        //         "message" => "Link tidak valid"
        //     ], 401);
        // }

        $verification = Verification::select("id", "transaction_sub_type_id", "name", "pic_name", "email", "phone_number", "website", "address", "status")->with(["emails:id,verification_id,name,address", "phones:id,verification_id,number,name", "documents" => function ($q) {
            $q->select(["id", "verification_id", "date", "file", "document_id"]);
            $q->whereNull("date");
            $q->whereNull("file");
            $q->with("document", function ($q) {
                $q->select("id", "name");
            });
        }, "subType:id,transaction_type_id,name,description" => ["transactionType:id,name"]])->where("id", $request->verification)->whereIn("status", ["verifications", "upload_file"])->first();

        if (!$verification) {
            return response()->json([
                "message" => "Data tidak ditemukan"
            ], 404);
        } elseif ($verification->status == "verifications") {
            return response()->json([
                "message" => "Terima kasih telah mengupload dokumen, mohon menunggu proses pengurusan dokumen."
            ], 201);
        }

        return response()->json([
            "data" => $verification,
            "file" => $verification->documents,
            "message" => "Silahkan upload file"
        ], 200);
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "document_id.*" => ["required"],
            "files.*" => ["required", "mimes:png,jpg,pdf,xls,xlsx,csv"]
        ], ["files.*.mimes" => "Jenis file tidak sesuai"]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                403
            );
        }

        try {
            $verification = Verification::where("id", $request->verification_id)->with(["documents"])->first();

            foreach ($request->document_id as $iDoc => $documentId) {
                if ($request->has("files.{$iDoc}")) {
                    $verificationDocument = $verification->documents->where("document_id", $documentId)->first();

                    if ($verificationDocument) {
                        $verificationDirectory = "private/verifications/{$verification->id}";
                        if (!Storage::exists($verificationDirectory)) {
                            Storage::makeDirectory($verificationDirectory); //creates directory
                        }

                        $documentDirectory = $verificationDirectory . "/documents";

                        if (!Storage::exists($documentDirectory)) {
                            Storage::makeDirectory($documentDirectory); //creates directory
                        }

                        if ($verificationDocument->file) {
                            Storage::delete($verification->documents->where("document_id", $documentId)->first()->file);
                        }

                        $path = Storage::putFile($documentDirectory, $request->file("files.{$iDoc}"), "private");

                        $verificationDocument->update([
                            "date" => Carbon::now(),
                            "file" => $path,
                        ]);
                    }

                    $verification->update([
                        "status" => "verifications"
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()]);
        }

        return response()->json(["message" => "Terima kasih telah mempercayakan pengurusan dokumen kapal kepada PT. Sinar Lautan Maritim. Mohon menunggu untuk proses selanjutnya. Terima kasih."]);
    }
}
