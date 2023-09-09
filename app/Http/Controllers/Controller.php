<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    const SOMETHING_WENT_WRONG = "Something Went Wrong";
    const UNAUTHORIZED = "Unauthorized";
    const SUCCESS_LOGOUT = "Success Logout";
    const SUCCESS_DELETE = "Success Delete";
    const SUCCESS = "success";
    const ERROR = "error";
}
