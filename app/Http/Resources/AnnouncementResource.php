<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Kisame76\FilamentAdvancedRichEditor\RichEditor\AdvancedRichContentRenderer;

class AnnouncementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => AdvancedRichContentRenderer::make($this->content)
                ->attribute($this->resource->getRichContentAttribute('content'))
                ->toHtml(),
            'status' => $this->status,
            'published_at' => $this->published_at,
            'created_by' => $this->creator?->name,
        ];
    }
}
