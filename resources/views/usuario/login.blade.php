@extends('layouts.app')

@section('content')

<div class="container login-section">
    <div class="login-card">
        
        {{-- Formulário --}}
        <form method="post" action="{{ route('autenticar') }}">
            @csrf

            {{-- Ícone --}}
            <div class="login-icon">
                <i class="ion-ios-locked"></i>
            </div>

            <h3 class="mb-4" style="color: #2c3e50; font-weight: 700;">Bem-vindo!</h3>

            {{-- Mensagens de Erro (Caso senha esteja errada) --}}
            @if($errors->any())
                <div class="alert alert-danger p-2" style="font-size: 0.9rem; border-radius: 10px;">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Campo Telefone --}}
            <div class="form-group mb-3">
                <input class="form-control custom-input" 
                       type="tel" 
                       name="telefone" 
                       placeholder="Seu Telefone" 
                       required 
                       autofocus>
            </div>

            {{-- Campo Senha --}}
            <div class="form-group mb-4">
                <input class="form-control custom-input" 
                       type="password" 
                       name="password" 
                       placeholder="Sua Senha" 
                       required>
            </div>

            {{-- Botão --}}
            <button class="btn btn-login-submit" type="submit">
                Entrar <i class="ion-log-in ml-2"></i>
            </button>

            {{-- Links de Apoio --}}
            <div class="login-links">
                <a href="#" class="forgot">Esqueceu sua senha?</a>
                
                <hr style="width: 50%; margin: 10px auto; opacity: 0.2;">
                
                <span>Ainda não tem conta?</span>
                <a href="{{ route('criarUsuario') }}" class="create-account-link">
                    Criar Usuário <i class="ion-android-person-add"></i>
                </a>
            </div>

        </form>
    </div>
</div>

@endsection