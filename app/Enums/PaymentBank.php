<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum PaymentBank: string
{
    case BCA = "bca";
    case BRI = "bri";
    case MANDIRI = "mandiri";
    case PERMATA = "permata";
    case BNI = "bni";
    case CIMB_NIAGA = "cimb niaga";

    public function labels(): string
    {
        return match ($this) {
            self::BCA => Str::upper("bca"),
            self::BRI => Str::upper("bri"),
            self::MANDIRI => Str::upper("mandiri"),
            self::PERMATA => Str::upper("permata"),
            self::BNI => Str::upper("bni"),
            self::CIMB_NIAGA => Str::upper("cimb niaga"),
        };
    }

    public static function array()
    {
        return collect([
            ["id" => "bca", "name" => Str::upper("bca")],
            ["id" => "bri", "name" => Str::upper("bri")],
            ["id" => "mandiri", "name" => Str::upper("mandiri")],
            ["id" => "permata", "name" => Str::upper("permata")],
            ["id" => "bni", "name" => Str::upper("bni")],
            ["id" => "cimb niaga", "name" => Str::upper("cimb niaga")],
        ]);
    }

    public function labelPowergridFilter(): string
    {
        return $this->labels();
    }
}
