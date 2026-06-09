<?php

namespace App\Services\Contact;

use App\Mail\NewContactLeadMail;
use App\Models\ContactLead;
use App\Services\Admin\AdminNotificationService;
use Illuminate\Support\Facades\Mail;

class ContactLeadService
{
    public function __construct(
        private readonly AdminNotificationService $notifications,
    ) {}

    public function store(array $data, ?int $userId = null): ContactLead
    {
        $lead = ContactLead::query()->create([
            'user_id' => $userId,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'source' => $data['source'] ?? 'contact_form',
            'status' => 'new',
        ]);

        $notifyEmail = config('laksana.contact.notify_email', config('laksana.brand.email'));
        Mail::to($notifyEmail)->send(new NewContactLeadMail($lead));

        $this->notifications->notifyLeadCreated($lead);

        return $lead;
    }
}
