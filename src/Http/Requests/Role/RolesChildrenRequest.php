<?php

/**
 * PHP version 7.3
 *
 * @package App\Http\Requests\Api\User\Role
 * @author  Oleksandr Barabolia <alexandrbarabolya@gmail.com>
 */

declare(strict_types=1);

namespace Mistery23\LaravelRoles\App\Http\Requests\Role;

use Mistery23\LaravelRoles\App\Http\Requests\RouteParameterValidation;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RolesRootRequest
 */
class RolesChildrenRequest extends FormRequest
{
    use RouteParameterValidation;


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
     * @return array
     */
    public function rules(): array
    {
        return [
            'roleId' => ['uuid'],
        ];
    }
}
