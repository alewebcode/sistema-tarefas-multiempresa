<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'company_name' => 'required|string|max:100',
            'company_identifier' => 'required|string|max:50|unique:companies,identifier'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create company first
        $company = Company::create([
            'name' => $request->company_name,
            'identifier' => $request->company_identifier,
            'email' => $request->email
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $company->id
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Usuário registrado com sucesso',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'company' => [
                    'id' => $company->id,
                    'name' => $company->name,
                    'identifier' => $company->identifier
                ]
            ],
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
       

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
     

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        return $this->createNewToken($token);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function refresh()
    {
        return $this->createNewToken(auth('api')->refresh());
    }

    public function profile()
    {
        $user = auth('api')->user();
        $user->load('company');

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'company' => [
                    'id' => $user->company->id,
                    'name' => $user->company->name,
                    'identifier' => $user->company->identifier
                ]
            ]
        ]);
    }

    protected function createNewToken($token)
    {
        $user = auth('api')->user();
        $user->load('company');

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'company' => [
                    'id' => $user->company->id,
                    'name' => $user->company->name,
                    'identifier' => $user->company->identifier
                ]
            ]
        ]);
    }
}