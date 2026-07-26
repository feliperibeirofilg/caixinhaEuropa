<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Services\EnvioEmailService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UsuarioController extends Controller
{
    public function formCriarUsuario(Request $request){

        return view("usuario.criar-usuario");
    }

    public function criarUsuario(UsuarioRequest $request, EnvioEmailService $envioEmailService){

        $request->validate();

        // Cria o usuario mas so loga apos validar o codigo de email

        $usuario = Usuario::create([
            'nome' => $request->nome,
            'login' => $request->login,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'admin' => $request->boolean('admin'),
            'password' => Hash::make($request->password),
        ]);

        $token = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);

        $envioEmailService->tokenEmail($usuario, $token);

        $request->session()->put('usuario_verificacao_id', $usuario->id);

        return redirect()->route('usuario.verificar-email');
    }

    public function formVerificarEmail(Request $request){

        if (!$request->session()->has('usuario_verificacao_id')) {
            return redirect()->route('formulario');
        }

        return view('usuario.verificar-email');
    }

    public function verificarEmail(Request $request){

        $request->validate([
            'token' => 'required|string|size:5',
        ]);

        $usuarioId = $request->session()->get('usuario_verificacao_id');

        if (!$usuarioId) {
            return redirect()->route('formulario');
        }

        $usuario = Usuario::find($usuarioId);

        if (!$usuario || $usuario->email_verification_token !== $request->token) {
            return back()->withErrors(['token' => 'Código inválido. Confira o e-mail e tente novamente.']);
        }

        $usuario->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        $request->session()->forget('usuario_verificacao_id');

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('caixinha.escolha.form')->with('success', 'Email verificado com sucesso!');
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
            return redirect()->route('caixinha.escolha.form');
        }
        return back()->withErrors(['telefone' => 'Telefone ou senha inválidos'])->withInput();
    }
}
