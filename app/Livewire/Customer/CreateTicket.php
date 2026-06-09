<?php

namespace App\Livewire\Customer;

use App\Models\SupportTicket;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.customer')]
#[Title('Buat Tiket Support')]
class CreateTicket extends Component
{
    #[Validate('required|string|max:255')]
    public string $subject = '';

    #[Validate('required|string|min:10')]
    public string $message = '';

    #[Validate('required|in:low,medium,high,urgent')]
    public string $priority = 'medium';

    public function submit(): void
    {
        $this->validate();

        $ticket = SupportTicket::query()->create([
            'ticket_number' => 'TKT-'.strtoupper(Str::random(8)),
            'user_id' => auth()->id(),
            'subject' => $this->subject,
            'priority' => $this->priority,
            'status' => 'open',
        ]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $this->message,
        ]);

        session()->flash('status', 'Tiket berhasil dibuat.');

        $this->redirect(route('customer.tickets'), navigate: true);
    }

    public function render()
    {
        return view('livewire.customer.create-ticket');
    }
}
