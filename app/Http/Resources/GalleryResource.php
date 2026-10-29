<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalleryResource extends JsonResource
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
            'images' => GalleryPhotoResource::collection($this->whenLoaded('photos')),
            'video_url' => $this->video_url,
            'description' => $this->description,
            'status' => $this->status,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'published_at' => $this->published_at?->diffForHumans(),
            'created_by' => $this->creator?->name,
        ];
    }
}
