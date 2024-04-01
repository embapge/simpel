<?php

namespace App\Enums;

// ["homeEquipment", "garment", "stationary", "food", "shipping"]
enum CustomerType: string
{
    case HOMEEQUIPMENT = "homeEquipment";
    case GARMENT = "garment";
    case STATIONARY = "stationary";
    case FOOD = "food";
    case SHIPPING = "shipping";

    public function labels(): string
    {
        return match ($this) {
            self::HOMEEQUIPMENT => "Home Equipment",
            self::GARMENT => "Garment",
            self::STATIONARY => "Stationary",
            self::FOOD => "Food",
            self::SHIPPING => "Shipping",
        };
    }

    public static function array()
    {
        return collect([
            ["id" => "homeEquipment", "name" => "Home Equipment"],
            ["id" => "garment", "name" => "Garment"],
            ["id" => "stationary", "name" => "Stationary"],
            ["id" => "food", "name" => "Food"],
            ["id" => "shipping", "name" => "Shipping"],
        ]);
    }

    public function labelPowergridFilter(): string
    {
        return $this->labels();
    }
}
