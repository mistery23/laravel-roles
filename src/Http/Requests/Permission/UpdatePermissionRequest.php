<?php

namespace Mistery23\LaravelRoles\App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdatePermissionRequest
 */
class UpdatePermissionRequest extends FormRequest
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
            'model'         => 'nullable|string',
            'parent_id'     => 'nullable|string',
            'description'   => 'nullable|string|max:128',
        ];
    }
}
