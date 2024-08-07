<?php

namespace Modules\Role\Http\Requests;

use App\Http\Requests\MainRequest;

class RoleRequest extends MainRequest
{
    public function rules()
    {
        return [
            "name" => "required"
        ];
    }
}
