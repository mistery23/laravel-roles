<?php

namespace jeremykenedy\LaravelRoles\App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class DetachPermissionRequest extends FormRequest
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
            'permission_id' => 'required|string',
        ];
    }
}