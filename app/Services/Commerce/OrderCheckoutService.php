<?php

namespace App\Services\Commerce;

use App\Models\DomainExtension;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PromoCode;
use App\Models\Template;
use App\Models\User;
use App\Services\Admin\AdminNotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderCheckoutService
{
    public function __construct(
        private readonly CheckoutPricingCalculator $pricing,
        private readonly AdminNotificationService $notifications,
    ) {}

    /** @param array<string, mixed> $data */
    public function createWebsiteOrder(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            $template = Template::query()->findOrFail($data['template_id']);
            $extension = DomainExtension::query()->findOrFail($data['domain_extension_id']);
            $years = (int) ($data['subscription_years'] ?? 1);
            $promoDiscount = (float) ($data['promo_discount'] ?? 0);
            $promoCode = $data['promo_code'] ?? null;
            $withEditing = (bool) ($data['with_editing_addon'] ?? false);

            $totals = $this->pricing->calculate(
                $extension,
                $template,
                $years,
                $withEditing,
                $promoDiscount,
            );

            $domainName = strtolower(trim((string) $data['domain_name']));
            $fullDomain = $domainName.(str_starts_with($extension->extension, '.')
                ? $extension->extension
                : '.'.$extension->extension);

            $order = Order::query()->create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $user->id,
                'type' => 'template',
                'checkout_flow' => $data['checkout_flow'] ?? 'standard',
                'status' => 'pending',
                'subtotal' => $totals['subtotal'],
                'discount' => $totals['discount'],
                'total' => $totals['total'],
                'currency' => config('laksana.payment.currency', 'IDR'),
                'notes' => $data['notes'] ?? null,
                'metadata' => [
                    'domain_name' => $domainName,
                    'domain_extension' => $extension->extension,
                    'full_domain' => $fullDomain,
                    'subscription_years' => $years,
                    'customer_name' => $data['customer_name'] ?? $user->name,
                    'customer_email' => $data['customer_email'] ?? $user->email,
                    'phone' => $data['phone'] ?? $user->phone,
                    'promo_code' => $promoCode,
                    'with_editing_addon' => $withEditing,
                    'fulfillment_status' => 'awaiting_payment',
                ],
            ]);

            $items = [
                [
                    'item_type' => 'domain',
                    'item_id' => $extension->id,
                    'name' => 'Domain '.$fullDomain.' ('.$years.' thn)',
                    'unit_price' => $totals['domain_price'],
                    'options' => ['years' => $years],
                ],
                [
                    'item_type' => 'template',
                    'item_id' => $template->id,
                    'name' => 'Template: '.$template->name,
                    'unit_price' => $totals['template_price'],
                ],
                [
                    'item_type' => 'hosting',
                    'item_id' => $years,
                    'name' => 'Hosting '.$years.' Tahun Berlangganan',
                    'unit_price' => $totals['hosting_price'],
                    'options' => ['years' => $years],
                ],
            ];

            if ($totals['admin_fee'] > 0) {
                $items[] = [
                    'item_type' => 'fee',
                    'item_id' => 0,
                    'name' => 'Biaya Admin',
                    'unit_price' => $totals['admin_fee'],
                ];
            }

            if ($totals['addon_price'] > 0) {
                $items[] = [
                    'item_type' => 'addon',
                    'item_id' => 0,
                    'name' => 'Jasa Editing Website',
                    'unit_price' => $totals['addon_price'],
                ];
            }

            foreach ($items as $item) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'item_type' => $item['item_type'],
                    'item_id' => $item['item_id'],
                    'name' => $item['name'],
                    'quantity' => 1,
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['unit_price'],
                    'options' => $item['options'] ?? null,
                ]);
            }

            if ($promoCode && $totals['discount'] > 0) {
                PromoCode::query()
                    ->where('code', $promoCode)
                    ->increment('used_count');
            }

            Invoice::query()->create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'status' => 'sent',
                'amount' => $totals['total'],
                'due_date' => now()->addDay(),
            ]);

            $this->notifications->notifyOrderCreated($order->load('user'));

            return $order->load(['items', 'invoices']);
        });
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
    }

    private function generateInvoiceNumber(): string
    {
        return 'INV-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
    }
}
