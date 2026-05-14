<?php

declare(strict_types=1);

namespace App\Http\Requests\Comment;

use App\Http\Requests\BaseApiRequest;

class StoreCommentRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'max:1000'],
        ];
    }
}
