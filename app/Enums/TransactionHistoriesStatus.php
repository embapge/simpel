<?php

namespace App\Enums;

enum TransactionHistoriesStatus: string
{
    case VERIFICATION = "verification";
    case PROGRESS = "progress";
    case DONE = "done";
    case CANCEL = "cancel";
}
