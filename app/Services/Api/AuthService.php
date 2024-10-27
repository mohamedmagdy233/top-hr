<?php

namespace App\Services\Api;

use App\Http\Resources\UserResource;
use App\Models\User as ObjModel;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthService extends BaseService
{

    public function __construct(ObjModel $model)
    {
        parent::__construct($model);
    }

    public function login($request): \Illuminate\Http\JsonResponse
    {

        $rules = [
            'phone' => 'required|exists:users,phone',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->jsonData('خطأ في البيانات', null, 422);
        }

        $credentials = [
            'phone' => $request->phone,
            'password' => $request->password,
        ];

        $token = Auth::guard('user-api')->attempt($credentials);
        $user = new UserResource(Auth::guard('user-api')->user());
        $user['token'] = $token;


        if ($token) {
            return $this->jsonData('تم تسجيل الدخول بنجاح', $user);
        }
        return $this->jsonData('خطأ في البيانات', null, 401);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        Auth::guard('user-api')->logout();
        return $this->jsonData('تم تسجيل الخروج بنجاح');
    }
}
