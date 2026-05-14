<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Http\Requests\BaseApiRequest;

class UpdateSelfRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
        ];
    }
}
