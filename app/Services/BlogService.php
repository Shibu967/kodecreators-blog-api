<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Blog;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class BlogService
{
    public function list(array $filters, ?int $userId): LengthAwarePaginator
    {
        $query = Blog::query()
            ->with(['user'])
            ->withCount(['comments', 'likes'])
            ->withExists(['likes as is_liked' => fn($q) => $q->where('user_id', $userId)]);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($u) =>
                        $u->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                    );
            });
        }

        $allowedSorts = ['title', 'description', 'comments_count', 'likes_count'];
        $sortBy = in_array($filters['sort_by'] ?? '', $allowedSorts, true)
            ? $filters['sort_by']
            : 'created_at';
        $sortDir = ($filters['sort_dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortBy, $sortDir);

        return $query->paginate(10);
    }

    public function create(array $data, ?UploadedFile $image): Blog
    {
        $data['image'] = $image ? $image->store('blogs', 'public') : null;

        return Blog::create($data);
    }

    public function update(Blog $blog, array $data, ?UploadedFile $image): Blog
    {
        if ($image) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $data['image'] = $image->store('blogs', 'public');
        }

        $blog->update($data);

        return Blog::with(['user'])
            ->withCount(['comments', 'likes'])
            ->withExists(['likes as is_liked' => fn($q) => $q->where('user_id', auth()->id())])
            ->findOrFail($blog->id);
    }

    public function delete(Blog $blog): void
    {
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();
    }
}
