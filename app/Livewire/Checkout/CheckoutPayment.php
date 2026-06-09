<?php

namespace App\Livewire\Checkout;

use App\Models\Invoice;
use App\Services\Payment\PaymentService;
use App\Services\Settings\PaymentSettingsService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.checkout-payment')]
#[Title('Metode Pembayaran')]
class CheckoutPayment extends Component
{
    public Invoice $invoice;

    public string $selectedMethod = 'bank_transfer';

    public function mount(Invoice $invoice): void
    {
        abort_unless(auth()->check() && auth()->id() === $invoice->user_id, 403);

        if ($invoice->status === 'paid') {
            redirect()->route('customer.orders')->send();
        }

        $this->invoice = $invoice->load(['order.items', 'user']);
    }

    public function pay(PaymentService $payments, PaymentSettingsService $paymentSettings)
    {
        if (! $paymentSettings->isEnabled()) {
            session()->flash('error', 'Pembayaran online sedang nonaktif. Hubungi admin.');

            return null;
        }

        $gateway = $paymentSettings->defaultGateway();

        if (! $paymentSettings->isConfigured($gateway)) {
            session()->flash('error', 'Payment gateway belum dikonfigurasi. Admin perlu mengisi API key di Payment Gateway.');

            return null;
        }

        $result = $payments->initiate($this->invoice, $gateway);

        if ($result->success && $result->redirectUrl) {
            return redirect()->away($result->redirectUrl);
        }

        if ($result->success) {
            session()->flash('status', 'Instruksi pembayaran siap. Tim kami akan menghubungi Anda.');

            return redirect()->route('customer.invoices');
        }

        session()->flash('error', $result->message ?? 'Gagal membuat pembayaran. Coba lagi atau hubungi kami.');

        return null;
    }

    public function render()
    {
        return view('livewire.checkout.checkout-payment', [
            'order' => $this->invoice->order,
            'methods' => [
                ['id' => 'bank_transfer', 'label' => 'Transfer Bank', 'note' => 'BCA, Mandiri, BNI, BRI, Permata +7'],
                ['id' => 'card', 'label' => 'Kartu Kredit / Debit', 'note' => 'Visa, Mastercard, JCB'],
                ['id' => 'retail', 'label' => 'Outlet Ritel', 'note' => 'Alfamart, Indomaret'],
                ['id' => 'ewallet', 'label' => 'E-Wallet', 'note' => 'OVO, ShopeePay, LinkAja +2'],
                ['id' => 'qris', 'label' => 'Pembayaran QR', 'note' => 'QRIS'],
            ],
        ]);
    }
}
