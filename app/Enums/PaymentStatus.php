<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum PaymentStatus: string
{

        // pending, in process, paid, cancel

    case PENDING = "pending";
    case IN_PROCESS = "in process";
    case PAID = "paid";
    case CANCEL = "cancel";

    public function labels(): string
    {
        return match ($this) {
            self::PENDING => Str::upper("pending"),
            self::PAID => Str::upper("paid"),
            self::CANCEL => Str::upper("cancel"),
            self::IN_PROCESS => Str::upper("in process"),
        };
    }

    public static function array()
    {
        return collect([
            ["id" => self::PENDING, "name" => Str::upper("pending")],
            ["id" => self::PAID, "name" => Str::upper("paid")],
            ["id" => self::IN_PROCESS, "name" => Str::upper("in process")],
            ["id" => self::CANCEL, Str::upper("cancel")],
        ]);
    }

    public function labelPowergridFilter(): string
    {
        return $this->labels();
    }
}
