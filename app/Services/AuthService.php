<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AuthService
{
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
        UserPreference::create(['user_id' => $user->id]);
        return [
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user
        ];
    }

    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.']
            ]);
        }

        return [
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return ['message' => 'Logged out'];
    }

    public function getUser(Request $request)
    {
        return $request->user();
    }

    public function getPreferences(Request $request)
    {
        return UserPreference::where('user_id', $request->query('user_id'))->first();
    }

    public function savePreferences(Request $request)
    {
        return UserPreference::updateOrCreate(
            ['user_id' => $request->user()->id],
            $request->only(['use_newsapi', 'use_gnews', 'use_mediastack'])
        );
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->input('old_password'), $user->password)) {
                throw ValidationException::withMessages([
                    'old_password' => ['Old password is incorrect.']
                ]);
            }
            $user->password = Hash::make($request->input('new_password'));
        }

        if ($request->hasFile('profile_pic')) {
            $path = $request->file('profile_pic')->store('profile_pics', 'public');
            $user->profile_pic = $path;
        }
        $user->save();
        return $user;
    }
}