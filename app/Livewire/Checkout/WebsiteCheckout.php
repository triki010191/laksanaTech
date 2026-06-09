<?php

namespace App\Livewire\Checkout;

use App\Models\DomainExtension;
use App\Models\Template;
use App\Services\Commerce\CheckoutPricingCalculator;
use App\Services\Commerce\DomainLookupService;
use App\Services\Commerce\OrderCheckoutService;
use App\Services\Commerce\PromoCodeService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Pesan Website')]
class WebsiteCheckout extends Component
{
    #[Url]
    public string $flow = 'template_first';

    public int $step = 1;

    public string $domainName = '';

    public ?int $domainExtensionId = null;

    public ?int $templateId = null;

    public int $subscriptionYears = 1;

    public string $customerName = '';

    public string $customerEmail = '';

    public string $phone = '';

    public string $promoCode = '';

    public ?string $appliedPromoCode = null;

    public float $promoDiscount = 0;

    public string $promoMessage = '';

    public bool $withEditingAddon = false;

    public bool $showConfirmModal = false;

    /** @var array<int, array<string, mixed>> */
    public array $domainResults = [];

    public bool $domainSearched = false;

    public string $templateSearch = '';

    public function mount(): void
    {
        if ($templateId = request()->integer('template')) {
            $this->templateId = $templateId;
        }

        if (Auth::check()) {
            $user = Auth::user();
            $this->customerName = $user->name ?? '';
            $this->customerEmail = $user->email ?? '';
            $this->phone = $user->phone ?? '';
        }

        $this->promoCode = config('laksana.checkout.default_promo', '');
    }

    public function getStepsProperty(): array
    {
        return config('laksana.checkout.steps', []);
    }

    public function searchDomains(DomainLookupService $lookup): void
    {
        $this->validate([
            'domainName' => 'required|string|min:3|max:63|regex:/^[a-z0-9-]+$/i',
        ], [
            'domainName.regex' => 'Gunakan huruf, angka, dan tanda hubung saja.',
        ]);

        $this->domainResults = $lookup->searchAll($this->domainName);
        $this->domainSearched = true;

        $firstAvailable = collect($this->domainResults)->firstWhere('available', true);
        if ($firstAvailable) {
            $this->domainExtensionId = $firstAvailable['extension_id'];
        }
    }

    public function applyPromo(PromoCodeService $promos): void
    {
        $result = $promos->apply($this->promoCode, $this->pricingSummary['subtotal']);
        $this->promoMessage = $result['message'];
        $this->appliedPromoCode = $result['valid'] ? $result['code'] : null;
        $this->promoDiscount = $result['valid'] ? $result['discount'] : 0;
    }

    public function getPricingSummaryProperty(): array
    {
        $extension = $this->domainExtensionId
            ? DomainExtension::query()->find($this->domainExtensionId)
            : null;
        $template = $this->templateId
            ? Template::query()->find($this->templateId)
            : null;

        return app(CheckoutPricingCalculator::class)->calculate(
            $extension,
            $template,
            $this->subscriptionYears,
            $this->withEditingAddon,
            $this->promoDiscount,
        );
    }

    public function getSelectedTemplateProperty(): ?Template
    {
        return $this->templateId ? Template::query()->with('category')->find($this->templateId) : null;
    }

    public function getSelectedExtensionProperty(): ?DomainExtension
    {
        return $this->domainExtensionId ? DomainExtension::find($this->domainExtensionId) : null;
    }

    public function getFilteredTemplatesProperty()
    {
        return Template::query()
            ->where('is_active', true)
            ->with('category')
            ->when($this->templateSearch, fn ($q) => $q->where('name', 'like', '%'.$this->templateSearch.'%'))
            ->orderByDesc('is_featured')
            ->orderByDesc('downloads_count')
            ->limit(12)
            ->get();
    }

    public function nextStep(): void
    {
        $key = $this->steps[$this->step - 1]['key'] ?? null;

        match ($key) {
            'domain' => $this->validateStepDomain(),
            'template' => $this->validate(['templateId' => 'required|exists:templates,id']),
            'data' => $this->validateStepData(),
            default => null,
        };

        if ($this->step < count($this->steps)) {
            $this->step++;
        }
    }

    public function prevStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function goToStep(int $step): void
    {
        if ($step >= 1 && $step <= $this->step) {
            $this->step = $step;
        }
    }

    public function openConfirmModal(): void
    {
        $this->validateStepDomain();
        $this->validate(['templateId' => 'required|exists:templates,id']);
        $this->validateStepData();
        $this->validate(['subscriptionYears' => 'required|integer|in:1,2,3']);

        if ($this->promoCode && ! $this->appliedPromoCode) {
            $this->applyPromo(app(PromoCodeService::class));
        }

        $this->showConfirmModal = true;
    }

    public function closeConfirmModal(): void
    {
        $this->showConfirmModal = false;
    }

    public function placeOrder(OrderCheckoutService $checkout)
    {
        if (! Auth::check()) {
            session()->put('checkout_return', url()->current().'?'.http_build_query([
                'flow' => $this->flow,
                'template' => $this->templateId,
            ]));

            return redirect()->route('login');
        }

        $this->validateStepDomain();
        $this->validate(['templateId' => 'required|exists:templates,id']);
        $this->validateStepData();

        $order = $checkout->createWebsiteOrder(Auth::user(), [
            'checkout_flow' => $this->flow,
            'domain_name' => $this->domainName,
            'domain_extension_id' => $this->domainExtensionId,
            'template_id' => $this->templateId,
            'subscription_years' => $this->subscriptionYears,
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'phone' => $this->phone,
            'promo_code' => $this->appliedPromoCode,
            'promo_discount' => $this->promoDiscount,
            'with_editing_addon' => $this->withEditingAddon,
        ]);

        $invoice = $order->invoices->first();
        $this->showConfirmModal = false;

        return redirect()->route('checkout.payment', $invoice);
    }

    private function validateStepDomain(): void
    {
        $this->validate([
            'domainName' => 'required|string|min:3|max:63',
            'domainExtensionId' => 'required|exists:domain_extensions,id',
        ]);

        if (! $this->domainSearched) {
            $this->searchDomains(app(DomainLookupService::class));
        }

        $selected = collect($this->domainResults)->firstWhere('extension_id', $this->domainExtensionId);

        if (! $selected || ! $selected['available']) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'domainExtensionId' => 'Domain tidak tersedia. Pilih ekstensi lain.',
            ]);
        }
    }

    private function validateStepData(): void
    {
        $this->validate([
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);
    }

    public function render()
    {
        return view('livewire.checkout.website-checkout', [
            'steps' => $this->steps,
            'currentStep' => $this->steps[$this->step - 1] ?? null,
            'pricing' => $this->pricingSummary,
            'subscriptionPlans' => config('laksana.checkout.subscription_plans', []),
        ]);
    }
}
