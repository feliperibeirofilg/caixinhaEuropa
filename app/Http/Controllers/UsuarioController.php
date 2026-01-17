<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UsuarioController extends Controller
{
    public function formCriarUsuario(Request $request){

        return view("usuario.criar-usuario");
    }

    public function criarUsuario(Request $request){

    $request->validate([
            'nome' => 'required|string|max:255',
            'login' => 'required|string|unique:usuarios',
            'telefone' => 'nullable|string|max:255',
            'admin' => 'boolean',
            'password' => 'required|string|min:6',
    ]);

    Usuario::create([
        'nome' => $request->nome,
        'login' => $request->login,
        'telefone' => $request->telefone,
        'admin' => $request->boolean('admin'),
        'password' => Hash::make($request->password),
    ]);

    return view('usuario.login');
    }

    public function login(Request $request){
        return view('usuario.login');
    }

    public function autenticar(Request $request){

        $credenciais = $request->validate([
            'telefone' => 'required|string',
            'password' => 'required|string',
        ]);

        if(Auth::attempt($credenciais)){
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }
        return back()->withErrors(['telefone' => 'Telefone ou senha inválidos'])->withInput();
    }
}
