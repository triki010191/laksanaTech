<?php

namespace App\Livewire\Admin\Settings;

use App\Services\Settings\IntegrationSettingsService;
use App\Services\Settings\SiteSettingsService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Integrasi API')]
class ManageIntegrationsSettings extends Component
{
    public string $integration_domain_provider = 'simulated';

    public bool $integration_domain_sandbox = true;

    public string $integration_domain_api_key = '';

    public string $integration_domain_api_secret = '';

    public string $integration_webhook_secret = '';

    public string $integration_api_notes = '';

    public function mount(IntegrationSettingsService $integrations): void
    {
        $data = $integrations->allForAdmin();
        $this->integration_domain_provider = $data['integration_domain_provider'];
        $this->integration_domain_sandbox = $data['integration_domain_sandbox'];
        $this->integration_api_notes = $data['integration_api_notes'];
    }

    public function save(SiteSettingsService $site): void
    {
        $this->validate([
            'integration_domain_provider' => 'required|in:simulated,resellerclub,rumahweb,nic,idwebhost',
        ]);

        $data = [
            'integration_domain_provider' => $this->integration_domain_provider,
            'integration_domain_sandbox' => $this->integration_domain_sandbox ? '1' : '0',
            'integration_api_notes' => $this->integration_api_notes,
        ];

        if ($this->integration_domain_api_key !== '') {
            $data['integration_domain_api_key'] = $this->integration_domain_api_key;
        }
        if ($this->integration_domain_api_secret !== '') {
            $data['integration_domain_api_secret'] = $this->integration_domain_api_secret;
        }
        if ($this->integration_webhook_secret !== '') {
            $data['integration_webhook_secret'] = $this->integration_webhook_secret;
        }

        $site->setMany($data, 'integration');

        $this->integration_domain_api_key = '';
        $this->integration_domain_api_secret = '';
        $this->integration_webhook_secret = '';

        session()->flash('status', 'Pengaturan integrasi API disimpan.');
    }

    public function render(IntegrationSettingsService $integrations)
    {
        return view('livewire.admin.settings.manage-integrations-settings', [
            'meta' => $integrations->allForAdmin(),
        ]);
    }
}
