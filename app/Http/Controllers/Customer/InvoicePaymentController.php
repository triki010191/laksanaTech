<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InvoicePaymentController extends Controller
{
    public function pay(Invoice $invoice, PaymentService $payments): RedirectResponse
    {
        if ($invoice->user_id !== auth()->id()) {
            abort(403);
        }

        if ($invoice->status === 'paid') {
            return redirect()->route('customer.invoices')
                ->with('status', 'Invoice sudah dibayar.');
        }

        $invoice->load('user');
        $result = $payments->initiate($invoice, 'midtrans');

        if (! $result->success || ! $result->redirectUrl) {
            return back()->with('error', $result->message ?? 'Gagal membuat pembayaran.');
        }

        return redirect()->away($result->redirectUrl);
    }

    public function finish(Request $request): RedirectResponse
    {
        return redirect()->route('customer.invoices')
            ->with('status', 'Terima kasih. Pembayaran sedang diproses.');
    }
}
