<?php

declare(strict_types=1);

namespace App\Http\Requests\Blog;

use App\Http\Requests\BaseApiRequest;

class UpdateBlogRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['sometimes', 'string', 'max:150'],
            'description' => ['sometimes', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
