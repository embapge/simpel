<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Document::factory()->count(100)->create();
        $faker = Faker::create("id_ID");

        $documents = [
            [
                "name" => "instruksi internal/nota dinas",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "sertifikat keselamatan kapal",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "removal of wrecks",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "sertifikat ballast water management",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "sertifikat dana jaminan ganti rugi pencemaran dari bahan bakar minyak (clc bunker)",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "sertifikat internasional pencegahan pencemaran oleh kotoran (ispp)",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "sertifikat internasional pencegahan pencemaran oleh minyak (iopp)",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "sertifikat internasional pencegahan pencemaran oleh udara (iapp)",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "sertifikat jaminan ganti rugi pencemaran (clc)",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "sertifikat pengendalian sistem anti teritip (afs)",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "sertifikat manajemen keselamatan kapal (smc)",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "sertifikat manajemen keselamatan perusahaan (doc)",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "sertifikat nasional pencegahan pencemaran oleh minyak (snpp)",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "penerbitan stkk (surat laut)",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "penerbitan stkk (surat laut sementara)",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "pergantian bendera",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "dokumen pengawakan (safe manning certificate)",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "pemeriksaan gambar safety & fire control plan",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "pengesahan buku perhitungan stabilitas kapal",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "pengesahan gambar kapal bangunan baru yang di bangun di galangkan kapal dalam negeri",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "otorisasi sertifikat garis muat kapal dalam rangka penerbitan pertama sertifikat",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "otorisasi sertifikat garis muat kapal dalam rangka pengukuhan sertifikat (endorsement)",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
            [
                "name" => "sertifikat garis muat kapal",
                "description" => $faker->text(),
                "is_active" => $faker->randomElement([0, 1])
            ],
        ];

        foreach ($documents as $document) {
            Document::create($document);
        }
    }
}
