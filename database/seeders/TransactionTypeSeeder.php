<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\TransactionDocumentTemplate;
use App\Models\TransactionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactionType = TransactionType::create([
            "name" => "Dokumen",
            "description" => "Layanan pengurusan dokumen kapal",
        ]);

        $kk = $transactionType->subTypes()->create(
            [
                "name" => "keselamatan kapan"
            ]
        );

        $transactionTemplate = TransactionDocumentTemplate::create(["name" => "Template 1"]);
        $documentKk = Document::whereIn("name", ["instruksi internal/nota dinas", "sertifikat keselamatan kapal"])->get()->map(fn (Document $document) => ["document_id" => $document->id, "transaction_document_template_id" => $transactionTemplate->id, "is_required" => 1]);

        $kk->documentTemplates()->attach($documentKk);

        $ppmkk = $transactionType->subTypes()->create(
            [
                "name" => "pencegahan pencemaran dan manajemen keselamatan kapal"
            ]
        );

        $transactionTemplate1 = TransactionDocumentTemplate::create(["name" => "Template 1"]);
        $documentPpmkk = Document::whereIn("name", ["sertifikat nasional pencegahan pencemaran oleh minyak (snpp)", "sertifikat manajemen keselamatan perusahaan (doc)", "sertifikat manajemen keselamatan kapal (smc)", "sertifikat pengendalian sistem anti teritip (afs)", "sertifikat jaminan ganti rugi pencemaran (clc)", "sertifikat internasional pencegahan pencemaran oleh udara (iapp)", "sertifikat internasional pencegahan pencemaran oleh minyak (iopp)", "sertifikat internasional pencegahan pencemaran oleh kotoran (ispp)", "sertifikat dana jaminan ganti rugi pencemaran dari bahan bakar minyak (clc bunker)", "sertifikat ballast water management", "removal of wrecks"])->get()->map(fn (Document $document) => ["document_id" => $document->id, "transaction_document_template_id" => $transactionTemplate1->id, "is_required" => 1]);
        $ppmkk->documentTemplates()->attach($documentPpmkk);

        $ppkk = $transactionType->subTypes()->create(
            [
                "name" => "pengukuran, pendaftaran dan kebangsaan kapal"
            ]
        );

        $transactionTemplate2 = TransactionDocumentTemplate::create(["name" => "Template 1"]);
        $documentPpkk = Document::whereIn("name", ["penerbitan stkk (surat laut)", "penerbitan stkk (surat laut sementara)", "pergantian bendera"])->get()->map(fn (Document $document) => ["document_id" => $document->id, "transaction_document_template_id" => $transactionTemplate2->id, "is_required" => 1]);
        $ppkk->documentTemplates()->attach($documentPpkk);

        $kpltn = $transactionType->subTypes()->create(
            [
                "name" => "kepelautan"
            ]
        );

        $transactionTemplate3 = TransactionDocumentTemplate::create(["name" => "Template 1"]);
        $documentKpltn = Document::whereIn("name", ["dokumen pengawakan (safe manning certificate)"])->get()->map(fn (Document $document) => ["document_id" => $document->id, "transaction_document_template_id" => $transactionTemplate3->id, "is_required" => 1]);
        $kpltn->documentTemplates()->attach($documentKpltn);

        $rbsgmk = $transactionType->subTypes()->create(
            [
                "name" => "rancang bangun, stabilitas dan garis muat kapal"
            ]
        );

        $transactionTemplate4 = TransactionDocumentTemplate::create(["name" => "Template 1"]);
        $documentrbsgmk = Document::whereIn("name", ["pengesahan buku perhitungan stabilitas kapal", "pengesahan gambar kapal bangunan baru yang di bangun di galangkan kapal dalam negeri", "otorisasi sertifikat garis muat kapal dalam rangka penerbitan pertama sertifikat", "otorisasi sertifikat garis muat kapal dalam rangka pengukuhan sertifikat (endorsement)", "sertifikat garis muat kapal"])->get()->map(fn (Document $document) => ["document_id" => $document->id, "transaction_document_template_id" => $transactionTemplate4->id, "is_required" => 1]);
        $rbsgmk->documentTemplates()->attach($documentrbsgmk);
    }
}
