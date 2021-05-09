<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponser; // Traiti qoşuruq
    // Qeydiyyat, bizi sistemə əlavə edir və Token göndərir.
    public function register(Request $request){
        // Qeydiyyat üçün tələblər
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);
        // User modeli ilə istifadəçi yaradırıq.
        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email']
        ]);
        // Yaranan istifadəçi üçün token göndəririk.
        return $this->success([
            'token'=>$user->createToken('API Token')->plainTextToken
        ]);
    }
    // Giriş, bizə Token göndərir
    public function login(Request $request){
        $attr = $request->validate([
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);
        // Əgər email vəya pass səhv olsa
        if(!Auth::attempt($attr)){
            return $this->error('Məlumatlar düzgün daxil edilməyib',  401);
        }
        // Əgər email və password düz olarsa
        return $this->success([
            'token'=>auth()->user()->createToken('API Token')->plainText
        ]);

    }
    // Çıxış, tokeni silir
    public function logout(){
        auth()->user()->tokens()->delete();

        return $this->success([
          'message'=>'Token silindi!'
        ]);
    }
}
