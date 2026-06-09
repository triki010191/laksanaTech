<?php

namespace App\Livewire\Admin\Leads;

use App\Models\ContactLead;
use App\Models\LeadReply;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Kelola Leads')]
class ManageLeads extends Component
{
    use WithPagination;

    #[Url(as: 'view')]
    public ?int $viewingId = null;

    public string $replyMessage = '';

    public function updateStatus(int $id, string $status): void
    {
        ContactLead::query()->findOrFail($id)->update(['status' => $status]);
        session()->flash('status', 'Status lead diperbarui.');
    }

    public function openReply(int $id): void
    {
        $this->viewingId = $id;
        $this->replyMessage = '';
    }

    public function closeReply(): void
    {
        $this->viewingId = null;
        $this->replyMessage = '';
    }

    public function sendReply(): void
    {
        $this->validate(['replyMessage' => 'required|string|min:3|max:2000']);

        $lead = ContactLead::query()->with('user')->findOrFail($this->viewingId);

        LeadReply::query()->create([
            'contact_lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'message' => $this->replyMessage,
            'is_staff' => true,
        ]);

        if ($lead->status === 'new') {
            $lead->update(['status' => 'contacted']);
        }

        $this->replyMessage = '';
        session()->flash('status', $lead->user_id
            ? 'Balasan tersimpan. Pelanggan terdaftar — notifikasi akun akan dikirim (fase berikutnya).'
            : 'Balasan tersimpan. Hubungi '.$lead->email.' via email/WhatsApp.');
    }

    public function render()
    {
        $viewing = $this->viewingId
            ? ContactLead::query()->with(['replies.user', 'user'])->find($this->viewingId)
            : null;

        return view('livewire.admin.leads.manage-leads', [
            'leads' => ContactLead::query()->latest()->paginate(15),
            'viewing' => $viewing,
        ]);
    }
}
