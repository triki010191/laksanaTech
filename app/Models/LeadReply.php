<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadReply extends Model
{
    protected $fillable = [
        'contact_lead_id',
        'user_id',
        'message',
        'is_staff',
    ];

    protected function casts(): array
    {
        return [
            'is_staff' => 'boolean',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(ContactLead::class, 'contact_lead_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
