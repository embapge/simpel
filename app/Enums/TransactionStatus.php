<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case DRAFT = "draft";
    case UNPAID = "unpaid";
    case PAID = "paid";
    case LESSPAID = "lesspaid";
    case CANCEL = "cancel";

    public function labels(): string
    {
        return match ($this) {
            self::DRAFT => "Draft",
            self::UNPAID => "Unpaid",
            self::PAID => "Paid",
            self::LESSPAID => "Lesspaid",
            self::CANCEL => "Cancel",
        };
    }

    public static function array()
    {
        return collect([
            ["id" => "draft", "name" => "❔Draft"],
            ["id" => "unpaid", "name" => "❌Unpaid"],
            ["id" => "paid", "name" => "🤑Paid"],
            ["id" => "lesspaid", "name" => "❕Lesspaid"],
            ["id" => "cancel", "name" => "🗑️Cancel"],
        ]);
    }

    public function labelPowergridFilter(): string
    {
        return $this->labels();
    }
}
