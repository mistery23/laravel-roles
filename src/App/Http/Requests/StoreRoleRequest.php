<?php

namespace jeremykenedy\LaravelRoles\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
            'name'          => 'required|unique:' . config('roles.rolesTable') . ',name,' . $this->id . ',id',
            'slug'          => 'required|unique:' . config('roles.rolesTable') . ',slug,' . $this->id . ',id',
            'description'   => 'nullable|string|max:128',
            'level'         => 'required|integer',
        ];
    }
}
