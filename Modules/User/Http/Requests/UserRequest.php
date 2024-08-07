<?php

namespace Modules\User\Http\Requests;

use App\Http\Requests\MainRequest;

class UserRequest extends MainRequest
{
    public function rules()
    {
        return [
            "email" => "required|email",
            "password" => "min:4"
        ];
    }
}
