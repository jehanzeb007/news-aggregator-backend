<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SavePreferencesRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // REGISTER
    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());
        return response()->json($result, 201);
    }

    // LOGIN
    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());
        return response()->json($result);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $result = $this->authService->logout($request);
        return response()->json($result);
    }

    // GET CURRENT USER
    public function user(Request $request)
    {
        return $this->authService->getUser($request);
    }

    // PREFERENCES
    public function getPreferences(Request $request)
    {   
        $prefs = $this->authService->getPreferences($request);
        return response()->json($prefs);
    }

    // SAVE PREFERENCES
    public function savePreferences(SavePreferencesRequest $request)
    {
        $prefs = $this->authService->savePreferences($request);
        return response()->json($prefs);
    }

    // UPDATE PROFILE
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $this->authService->updateProfile($request);
        return response()->json($user);
    }
}