<?php

namespace Mistery23\LaravelRoles\App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DetachRoleRequest
 */
class DetachRoleRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return boolean
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'role_id' => 'required|string',
        ];
    }
}
