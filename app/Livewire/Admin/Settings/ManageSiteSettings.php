<?php

namespace App\Livewire\Admin\Settings;

use App\Services\Settings\SiteSettingsService;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Pengaturan Website')]
class ManageSiteSettings extends Component
{
    use WithFileUploads;

    public string $activeTab = 'brand';

    public bool $promo_enabled = true;

    public string $promo_text = '';

    public string $promo_link_label = '';

    public $brand_logo;

    public ?string $current_logo_url = null;

    public string $footer_tagline = '';

    public string $footer_copyright = '';

    public string $whatsapp_number = '';

    public string $contact_phone = '';

    public bool $whatsapp_float_enabled = true;

    public string $whatsapp_float_message = '';

    public string $facebook_url = '';

    public string $instagram_url = '';

    public string $linkedin_url = '';

    public bool $cta_enabled = true;

    public string $cta_title = '';

    public string $cta_subtitle = '';

    public string $cta_button_label = '';

    public string $cta_button_url = '';

    public string $design_badge = '';

    public string $design_title = '';

    public string $design_subtitle = '';

    public string $design_sidebar_note = '';

    public string $design_sidebar_text = '';

    public function mount(SiteSettingsService $settings): void
    {
        $s = $settings->all();
        $this->promo_enabled = ($s['promo_enabled'] ?? '1') === '1';
        $this->promo_text = $s['promo_text'] ?? '';
        $this->promo_link_label = $s['promo_link_label'] ?? '';
        $this->footer_tagline = $s['footer_tagline'] ?? '';
        $this->footer_copyright = $s['footer_copyright'] ?? '';
        $this->whatsapp_number = $s['whatsapp_number'] ?? '';
        $this->contact_phone = $s['contact_phone'] ?? config('laksana.brand.phone', '');
        $this->whatsapp_float_enabled = ($s['whatsapp_float_enabled'] ?? '1') === '1';
        $this->whatsapp_float_message = $s['whatsapp_float_message'] ?? '';
        $this->facebook_url = $s['facebook_url'] ?? '';
        $this->instagram_url = $s['instagram_url'] ?? '';
        $this->linkedin_url = $s['linkedin_url'] ?? '';
        $this->cta_enabled = ($s['cta_enabled'] ?? '1') === '1';
        $this->cta_title = $s['cta_title'] ?? '';
        $this->cta_subtitle = $s['cta_subtitle'] ?? '';
        $this->cta_button_label = $s['cta_button_label'] ?? '';
        $this->cta_button_url = $s['cta_button_url'] ?? '';
        $this->design_badge = $s['design_badge'] ?? '';
        $this->design_title = $s['design_title'] ?? '';
        $this->design_subtitle = $s['design_subtitle'] ?? '';
        $this->design_sidebar_note = $s['design_sidebar_note'] ?? '';
        $this->design_sidebar_text = $s['design_sidebar_text'] ?? '';

        if (! empty($s['brand_logo_path'])) {
            $this->current_logo_url = upload_url($s['brand_logo_path']);
        }
    }

    public function save(SiteSettingsService $settings): void
    {
        $this->validate([
            'whatsapp_number' => 'nullable|string|max:20',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        $normalizedWa = $settings->normalizeWhatsappNumber($this->whatsapp_number ?: null);

        $data = [
            'promo_enabled' => $this->promo_enabled ? '1' : '0',
            'promo_text' => $this->promo_text,
            'promo_link_label' => $this->promo_link_label,
            'footer_tagline' => $this->footer_tagline,
            'footer_copyright' => $this->footer_copyright,
            'whatsapp_number' => $normalizedWa,
            'contact_phone' => $this->contact_phone ?: config('laksana.brand.phone'),
            'whatsapp_float_enabled' => $this->whatsapp_float_enabled ? '1' : '0',
            'whatsapp_float_message' => $this->whatsapp_float_message,
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
            'linkedin_url' => $this->linkedin_url,
            'cta_enabled' => $this->cta_enabled ? '1' : '0',
            'cta_title' => $this->cta_title,
            'cta_subtitle' => $this->cta_subtitle,
            'cta_button_label' => $this->cta_button_label,
            'cta_button_url' => $this->cta_button_url,
            'design_badge' => $this->design_badge,
            'design_title' => $this->design_title,
            'design_subtitle' => $this->design_subtitle,
            'design_sidebar_note' => $this->design_sidebar_note,
            'design_sidebar_text' => $this->design_sidebar_text,
        ];

        if ($this->brand_logo) {
            $this->validate(['brand_logo' => 'image|max:2048']);
            $path = $this->brand_logo->store('brand', 'public');
            $old = $settings->get('brand_logo_path');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $data['brand_logo_path'] = $path;
            $this->current_logo_url = upload_url($path);
        }

        $settings->setMany($data);
        session()->flash('status', 'Pengaturan website disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.settings.manage-site-settings');
    }
}
