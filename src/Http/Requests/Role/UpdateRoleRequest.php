<?php

namespace Mistery23\LaravelRoles\App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRoleRequest
 */
class UpdateRoleRequest extends FormRequest
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
            'name'          => 'required|string|max:64',
            'slug'          => 'required|string|max:64',
            'description'   => 'nullable|string|max:128',
            'level'         => 'required|integer',
        ];
    }
}
