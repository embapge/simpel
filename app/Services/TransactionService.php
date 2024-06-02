<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public Transaction $transaction;
    public function setTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    public function getTransaction()
    {
        return $this->transaction;
    }

    public function getNullNumber($date)
    {
        $transactions = Transaction::where(DB::raw("DATE_FORMAT('created_at', '%Y')"), $date)->whereNotNull("number_display")->orderBy("number_display")->get();
        $skipNumber = null;
        for ($i = 0; $i < $transactions->count(); $i++) {
            if (str_pad(explode("/", $transactions[$i])[0], 5, 0, STR_PAD_LEFT) != str_pad($i, 5, 0, STR_PAD_LEFT)) {
                $skipNumber = $i;
                break 1;
            }
        }

        return $skipNumber;
    }

    public function getNumberDisplay($date)
    {
        $transaction = Transaction::where(DB::raw("DATE_FORMAT(created_at, '%Y')"), Carbon::parse($date)->format('Y'))->whereNotNull("number_display")->orderByDesc("number_display")->latest()->first();
        return $transaction ? explode("/", $transaction->number_display)[0] + 1 : 1;
    }

    public function generateNumberDisplay($date)
    {
        $number = $this->getNullNumber($date);
        if (!$number) {
            $number = $this->getNumberDisplay($date);
        }

        $this->transaction->update([
            "number_display" => str_pad($number, 5, 0, STR_PAD_LEFT) . "/SMPL/TRNSCTN/" . Carbon::parse($date)->format("Y")
        ]);

        return $this->transaction;
    }
}
