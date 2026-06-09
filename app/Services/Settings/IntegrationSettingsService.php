<?php

namespace App\Services\Settings;

class IntegrationSettingsService
{
    public function __construct(
        private readonly SiteSettingsService $site,
    ) {}

    public function domainProvider(): string
    {
        return $this->site->get('integration_domain_provider', 'simulated') ?? 'simulated';
    }

    public function domainApiKey(): ?string
    {
        return $this->site->get('integration_domain_api_key') ?: null;
    }

    public function domainApiSecret(): ?string
    {
        return $this->site->get('integration_domain_api_secret') ?: null;
    }

    public function domainSandbox(): bool
    {
        return ($this->site->get('integration_domain_sandbox', '1') ?? '1') === '1';
    }

    public function webhookSecret(): ?string
    {
        return $this->site->get('integration_webhook_secret') ?: null;
    }

    public function apiNotes(): ?string
    {
        return $this->site->get('integration_api_notes');
    }

    /** @return array<string, mixed> */
    public function allForAdmin(): array
    {
        return [
            'integration_domain_provider' => $this->domainProvider(),
            'integration_domain_sandbox' => $this->domainSandbox(),
            'has_domain_api_key' => filled($this->domainApiKey()),
            'has_domain_api_secret' => filled($this->domainApiSecret()),
            'has_webhook_secret' => filled($this->webhookSecret()),
            'integration_api_notes' => $this->apiNotes() ?? '',
        ];
    }
}
