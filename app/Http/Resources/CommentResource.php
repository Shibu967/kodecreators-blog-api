<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'description' => $this->description,
            'likes_count' => $this->likes_count ?? 0,
            'is_liked'    => $this->whenLoaded('likes', fn() =>
                $this->likes->contains('user_id', $request->user()?->id)
            , false),
            'user'        => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ],
            'blog_id'     => $this->blog_id,
            'created_at'  => $this->created_at,
        ];
    }
}
