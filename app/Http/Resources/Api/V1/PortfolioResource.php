<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'client_name' => $this->client_name,
            'client_logo' => $this->client_logo,
            'project_url' => $this->project_url,
            'live_demo_link' => $this->live_demo_link,
            'screenshot_url' => $this->live_demo_link ? screenshot_url($this->live_demo_link) : null,
            'cover_image_url' => $this->cover_image_url,
            'short_description' => $this->short_description,
            'technologies' => $this->technologies,
            'completed_at' => $this->completed_at?->toDateString(),
            'images' => $this->whenLoaded('images', fn () => $this->images->map(fn ($img) => [
                'path' => $img->path,
                'caption' => $img->caption,
            ])),
        ];
    }
}
