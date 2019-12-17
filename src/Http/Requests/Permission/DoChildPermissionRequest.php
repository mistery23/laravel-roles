<?php

namespace Mistery23\LaravelRoles\App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class DoChildPermissionRequest extends FormRequest
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
            'parent_id' => 'required|string|max:64',
        ];
    }
}
