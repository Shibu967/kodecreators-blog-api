<?php

declare(strict_types=1);

namespace App\Http\Requests\Like;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class ToggleLikeRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(['BLOG', 'COMMENT'])],
            'id'   => ['required', 'integer', 'min:1'],
        ];
    }
}
