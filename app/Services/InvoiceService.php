<?php

namespace App\Services;

use App\Enums\InvoiceType;
use App\Models\Invoice;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    private Invoice $invoice;
    public function __construct()
    {
    }

    public function setInvoice(Invoice $invoice)
    {
        $this->invoice = $invoice;
        return $this;
    }

    public function getInvoice()
    {
        return $this->invoice;
    }

    public function getNullNumber($date)
    {
        $invoices = Invoice::where(DB::raw("DATE_FORMAT('created_at', '%Y')"), $date)->whereNotNull("number_display")->orderBy("number_display")->get();
        $skipNumber = null;
        for ($i = 0; $i < $invoices->count(); $i++) {
            if (str_pad(explode("/", $invoices[$i])[0], 5, 0, STR_PAD_LEFT) != str_pad($i, 5, 0, STR_PAD_LEFT)) {
                $skipNumber = $i;
                break 1;
            }
        }

        return $skipNumber;
    }

    public function getNumber($date)
    {
        $invoice = Invoice::where(DB::raw("DATE_FORMAT(created_at, '%Y')"), Carbon::parse($date)->format('Y'))->whereNotNull("number_display")->whereNot("number_display", "DRAFT")->orderByDesc("number_display")->latest()->first();
        return $invoice ? explode("/", $invoice->number_display)[0] + 1 : 1;
    }

    public function generateNumberDisplay($date)
    {
        $this->invoice->update([
            "type" => InvoiceType::INV
        ]);
        $this->invoice->refresh();
        $number = $this->getNullNumber($date);
        if (!$number) {
            $number = $this->getNumber($date);
        }

        $numberDisplay = $this->getNumberDisplay($number);

        $this->invoice->update([
            "number_display" => $numberDisplay
        ]);

        return $this->invoice;
    }

    public function getNumberDisplay($number)
    {
        $this->invoice->refresh();

        $display = "UNDFND";
        if ($this->invoice->type == InvoiceType::PRO->value) {
            $display = "PRO";
        } else if ($this->invoice->type == InvoiceType::KW->value) {
            $display = "KW";
        } else if ($this->invoice->type == InvoiceType::KEU->value) {
            $display = "KEU";
        } else if ($this->invoice->type == InvoiceType::INV->value) {
            $display = "INV";
        }

        return str_pad($number, 5, 0, STR_PAD_LEFT) . "/SMPL/{$display}/" . Carbon::parse($this->invoice->issue_date)->format("Y");
    }
}
