<?php

namespace App\Http\Requests;

class SSRRequest extends MainRequest
{
    public function rules()
    {
        return [
            "itemsPerPage" => "required|numeric",
            "page" => "required|numeric",
        ];
    }
}
