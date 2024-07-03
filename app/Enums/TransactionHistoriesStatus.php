<?php

namespace App\Enums;

enum TransactionHistoriesStatus: string
{
    case VERIFICATION = "verification";
    case PROGRESS = "progress";
    case DONE = "done";
    case CANCEL = "cancel";

    public static function toArray(): array
    {
        return [
            ["id" => self::VERIFICATION, "name" => "Verifikasi"],
            ["id" => self::PROGRESS, "name" => "Progress"],
            ["id" => self::DONE, "name" => "Selesai"],
            ["id" => self::CANCEL, "name" => "Dibatalkan"],
        ];
    }
}
