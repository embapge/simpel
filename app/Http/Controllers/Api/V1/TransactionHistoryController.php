<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
    public function show($transaction)
    {
        $histories = TransactionHistory::whereHas("transaction", function ($q) use ($transaction) {
            $q->where("id", $transaction);
        })->select("date", "status", "description", "type")->get();

        return response()->json($histories);
    }
}
