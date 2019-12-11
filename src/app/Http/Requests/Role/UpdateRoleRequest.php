<?php

namespace Mistery23\LaravelRoles\App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        if (config('roles.rolesGuiCreateNewRolesMiddlewareType') == 'role') {
//            return $this->user()->hasRole(config('roles.rolesGuiCreateNewRolesMiddleware'));
//        }
//        if (config('roles.rolesGuiCreateNewRolesMiddlewareType') == 'permissions') {
//            return $this->user()->hasPermission(config('roles.rolesGuiCreateNewRolesMiddleware'));
//        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'required|string|max:64',
            'slug'          => 'required|string|max:64',
            'description'   => 'nullable|string|max:128',
            'level'         => 'required|integer',
        ];
    }
}
