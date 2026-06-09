<?php

namespace App\Livewire\Admin\Settings;

use App\Services\Settings\PaymentSettingsService;
use App\Services\Settings\SiteSettingsService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Payment Gateway')]
class ManagePaymentSettings extends Component
{
    public bool $payment_enabled = true;

    public string $payment_default_gateway = 'xendit';

    public bool $payment_midtrans_is_production = false;

    public string $payment_midtrans_server_key = '';

    public string $payment_midtrans_client_key = '';

    public string $payment_xendit_secret_key = '';

    public string $payment_xendit_public_key = '';

    public bool $payment_tripay_is_production = false;

    public string $payment_tripay_merchant_code = '';

    public string $payment_tripay_api_key = '';

    public string $payment_tripay_private_key = '';

    public function mount(PaymentSettingsService $payments): void
    {
        $meta = $payments->allForAdmin();
        $this->payment_enabled = $meta['payment_enabled'];
        $this->payment_default_gateway = $meta['payment_default_gateway'];
        $this->payment_midtrans_is_production = $meta['payment_midtrans_is_production'];
        $this->payment_tripay_is_production = $meta['payment_tripay_is_production'];
    }

    public function save(SiteSettingsService $site, PaymentSettingsService $payments): void
    {
        $this->validate([
            'payment_default_gateway' => 'required|in:'.implode(',', $payments->availableGateways()),
        ]);

        $data = [
            'payment_enabled' => $this->payment_enabled ? '1' : '0',
            'payment_default_gateway' => $this->payment_default_gateway,
            'payment_midtrans_is_production' => $this->payment_midtrans_is_production ? '1' : '0',
            'payment_tripay_is_production' => $this->payment_tripay_is_production ? '1' : '0',
        ];

        if ($this->payment_midtrans_server_key !== '') {
            $data['payment_midtrans_server_key'] = $this->payment_midtrans_server_key;
        }
        if ($this->payment_midtrans_client_key !== '') {
            $data['payment_midtrans_client_key'] = $this->payment_midtrans_client_key;
        }
        if ($this->payment_xendit_secret_key !== '') {
            $data['payment_xendit_secret_key'] = $this->payment_xendit_secret_key;
        }
        if ($this->payment_xendit_public_key !== '') {
            $data['payment_xendit_public_key'] = $this->payment_xendit_public_key;
        }
        if ($this->payment_tripay_merchant_code !== '') {
            $data['payment_tripay_merchant_code'] = $this->payment_tripay_merchant_code;
        }
        if ($this->payment_tripay_api_key !== '') {
            $data['payment_tripay_api_key'] = $this->payment_tripay_api_key;
        }
        if ($this->payment_tripay_private_key !== '') {
            $data['payment_tripay_private_key'] = $this->payment_tripay_private_key;
        }

        $site->setMany($data, 'payment');

        $this->payment_midtrans_server_key = '';
        $this->payment_midtrans_client_key = '';
        $this->payment_xendit_secret_key = '';
        $this->payment_xendit_public_key = '';
        $this->payment_tripay_merchant_code = '';
        $this->payment_tripay_api_key = '';
        $this->payment_tripay_private_key = '';

        session()->flash('status', 'Pengaturan payment gateway disimpan.');
    }

    public function render(PaymentSettingsService $payments)
    {
        return view('livewire.admin.settings.manage-payment-settings', [
            'gateways' => $payments->labels(),
            'meta' => $payments->allForAdmin(),
            'gatewayConfigured' => $payments->isConfigured($this->payment_default_gateway),
        ]);
    }
}
