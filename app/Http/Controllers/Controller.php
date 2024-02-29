<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // Метод для возврата успешных действий
    public function successer($data, $status = 200)
    {
        return response()->json([
            "result" => "success",
            "data" => $data
        ], $status);
    }
    // Метод для возврата безуспешных действий
    public function failer($errors)
    {
        return response()->json(["result"=>"fail",
            "errors"=>$errors]);
    }
}
