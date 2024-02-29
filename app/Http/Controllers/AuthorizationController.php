<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthorizationController extends Controller
{
    // Регистрация нового пользователя
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        // Создание пользователя
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $user->createWallet();

        // Генерация токена Sanctum для нового пользователя
        $token = $user->createToken('token-name')->plainTextToken;

        // Возвращаем токен в ответе
        return $this->successer(['token' => $token]);
    }
    public function login(LoginRequest $request)
    {
        // Валидация данных
        $credentials = $request->validated();

        // Проверка учетных данных и аутентификация пользователя
        if (!Auth::attempt($credentials)) {
            return $this->failer(['email' => ['The provided credentials are incorrect.']]);
        }

        // Получение аутентифицированного пользователя
        $user = Auth::user();

        // Генерация токена Sanctum для аутентифицированного пользователя
        $token = $user->createToken('token-name')->plainTextToken;

        // Возвращаем токен в ответе
        return $this->successer(['token' => $token]);
    }
    // Выход пользователя
    public function logout()
    {
        // Удаление всех токенов пользователя из базы данных
        Auth::user()->tokens()->delete();

        // Возвращаем ответ
        return $this->successer(['message' => 'Log out']);
    }
}
