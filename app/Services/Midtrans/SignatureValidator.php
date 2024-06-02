<?php

namespace App\Services\Midtrans;

use Illuminate\Http\Request;
use Spatie\WebhookClient\Exceptions\InvalidConfig;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator as SpatieSignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class SignatureValidator implements SpatieSignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        return hash("sha512", "{$request["order_id"]}{$request["status_code"]}{$request["gross_amount"]}{$config->signingSecret}") === $request["signature_key"];
    }
}
