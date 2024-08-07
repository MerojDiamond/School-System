<?php

namespace Modules\User\Http\Requests;

use App\Http\Requests\MainRequest;

class UpdateUserRequest extends MainRequest
{
    public function rules()
    {
        return [
            "name" => "required",
            "email" => "required|email",
        ];
    }
}
