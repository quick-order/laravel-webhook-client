<?php

namespace Spatie\WebhookClient;

use Spatie\WebhookClient\Exceptions\InvalidConfig;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;
use Spatie\WebhookClient\WebhookResponse\DefaultRespondsTo;
use Spatie\WebhookClient\WebhookResponse\RespondsToWebhook;

class WebhookConfig
{
    public $name;

    /**
     * @var mixed|string
     */

    public $signingSecret;

    /**
     * @var mixed|string
     */

    public $signatureHeaderName;

    /**
     * @var \Illuminate\Contracts\Foundation\Application|mixed|SignatureValidator
     */

    public $signatureValidator;

    /**
     * @var \Illuminate\Contracts\Foundation\Application|mixed|WebhookProfile
     */

    public $webhookProfile;

    /**
     * @var \Illuminate\Contracts\Foundation\Application|mixed|RespondsToWebhook
     */

    public $webhookResponse;

    /**
     * @var mixed|string
     */

    public $webhookModel;

    /**
     * @var mixed|ProcessWebhookJob|string
     */

    public $processWebhookJobClass;

    /**
     * WebhookConfig constructor.
     * @param array $properties
     * @throws InvalidConfig
     */

    public function __construct(array $properties)
    {
        $this->name = $properties['name'];

        $this->signingSecret = $properties['signing_secret'] ?? '';

        $this->signatureHeaderName = $properties['signature_header_name'] ?? '';

        if (! is_subclass_of($properties['signature_validator'], SignatureValidator::class)) {
            throw InvalidConfig::invalidSignatureValidator($properties['signature_validator']);
        }
        $this->signatureValidator = app($properties['signature_validator']);

        if (! is_subclass_of($properties['webhook_profile'], WebhookProfile::class)) {
            throw InvalidConfig::invalidWebhookProfile($properties['webhook_profile']);
        }
        $this->webhookProfile = app($properties['webhook_profile']);

        $webhookResponseClass = $properties['webhook_response'] ?? DefaultRespondsTo::class;
        if (! is_subclass_of($webhookResponseClass, RespondsToWebhook::class)) {
            throw InvalidConfig::invalidWebhookResponse($webhookResponseClass);
        }
        $this->webhookResponse = app($webhookResponseClass);

        $this->webhookModel = $properties['webhook_model'];

        if (! is_subclass_of($properties['process_webhook_job'], ProcessWebhookJob::class)) {
            throw InvalidConfig::invalidProcessWebhookJob($properties['process_webhook_job']);
        }
        $this->processWebhookJobClass = $properties['process_webhook_job'];
    }
}
