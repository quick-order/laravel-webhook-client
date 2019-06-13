<?php

namespace Spatie\WebhookClient\SignatureValidator;

use Illuminate\Http\Request;
use Spatie\StripeWebhooks\Exceptions\WebhookFailed;
use Spatie\WebhookClient\WebhookConfig;

class DefaultSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config)
    {
        $signature = $request->header($config->signatureHeaderName);

        if (! $signature) {
            throw WebhookFailed::missingSignature($config->signatureHeaderName);
        }

        $signingSecret = $config->signingSecret;

        if (empty($signingSecret)) {
            throw WebhookFailed::signingSecretNotSet();
        }

        $computedSignature = hash_hmac('sha256', $request->getContent(), $signingSecret);

        return hash_equals($signature, $computedSignature);
    }
}
