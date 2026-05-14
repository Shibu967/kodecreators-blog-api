<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'description'    => $this->description,
            'image'          => $this->image ? asset('storage/' . $this->image) : null,
            'comments_count' => $this->comments_count ?? 0,
            'likes_count'    => $this->likes_count ?? 0,
            'is_liked'       => $this->whenLoaded('likes', fn() =>
                $this->likes->contains('user_id', $request->user()?->id)
            , false),
            'user'           => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ],
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
