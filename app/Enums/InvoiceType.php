<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum InvoiceType: string
{
    case PRO = "pro";
    case INV = "inv";
    case KW = "kw";
    case KEU = "keu";

    public function labels(): string
    {
        return match ($this) {
            self::PRO => Str::upper("pro"),
            self::INV => Str::upper("inv"),
            self::KW => Str::upper("kw"),
            self::KEU => Str::upper("keu"),
        };
    }

    public static function array()
    {
        return collect([
            ["id" => "pro", "name" => Str::upper("pro")],
            ["id" => "inv", "name" => Str::upper("inv")],
            ["id" => "kw", "name" => Str::upper("kw")],
            ["id" => "keu", "name" => Str::upper("keu")],
        ]);
    }

    public function labelPowergridFilter(): string
    {
        return $this->labels();
    }
}
