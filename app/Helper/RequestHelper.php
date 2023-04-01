<?php

namespace App\Helper;

use Illuminate\Http\Request;

class RequestHelper
{
    public static function build(array $attributes = null) {
        $request = new Request();
        if($attributes) {
            $request->request->add($attributes);
        }
        return $request;
    }
}
