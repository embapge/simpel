<?php

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('App.Models.Payment.{id}', function ($payment, $id) {
    return true;
});

Broadcast::channel('registration.{verification}', function (User $user, $verification) {
    return true;
});
// Broadcast::channel('generate.number.{transaction}', function (User $user, Transaction $transaction) {
//     return Auth::check();
// });
