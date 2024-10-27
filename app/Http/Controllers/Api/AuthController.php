<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\Api\AuthService as ObjService;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function __construct(protected ObjService $objService){}

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->objService->login($request);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        return $this->objService->logout();
    }

}//end class
