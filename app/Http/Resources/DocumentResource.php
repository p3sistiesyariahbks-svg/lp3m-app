<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'thumbnail' => new MediaResource($this->getFirstMedia('thumbnails')),
            'title' => $this->title,
            'slug' => $this->slug,
            'document' => new MediaResource($this->getFirstMedia('documents')),
            'file_url' => $this->file_url,
            'status' => $this->status,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'published_at' => $this->published_at?->diffForHumans(),
            'created_by' => $this->creator?->name,
        ];
    }
}
