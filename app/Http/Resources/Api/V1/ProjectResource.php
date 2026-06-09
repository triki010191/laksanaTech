<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'status' => $this->status,
            'progress' => $this->progress,
            'target_date' => $this->target_date?->toDateString(),
            'milestones' => $this->whenLoaded('milestones', fn () => $this->milestones->map(fn ($m) => [
                'title' => $m->title,
                'status' => $m->status,
                'due_date' => $m->due_date?->toDateString(),
            ])),
        ];
    }
}
