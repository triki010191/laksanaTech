<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'demo_url' => $this->demo_url,
            'live_demo_url' => $this->live_demo_url,
            'live_demo_link' => $this->live_demo_link,
            'screenshot_url' => $this->live_demo_link ? screenshot_url($this->live_demo_link) : null,
            'preview_image' => $this->preview_image,
            'is_featured' => $this->is_featured,
            'category' => $this->whenLoaded('category', fn () => [
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
        ];
    }
}
