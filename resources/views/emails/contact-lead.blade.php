<x-mail::message>
# Lead Baru dari Website

**Nama:** {{ $lead->name }}  
**Email:** {{ $lead->email }}  
@if($lead->phone)
**Telepon:** {{ $lead->phone }}  
@endif
@if($lead->subject)
**Subjek:** {{ $lead->subject }}  
@endif

**Pesan:**

{{ $lead->message }}

---

Sumber: {{ $lead->source }}  
Waktu: {{ $lead->created_at->format('d M Y H:i') }}

<x-mail::button :url="config('app.url').'/admin/leads'">
Lihat di Admin
</x-mail::button>

</x-mail::message>
