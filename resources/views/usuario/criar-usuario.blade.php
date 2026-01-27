@extends('layouts.app')

@section('content')

<div class="container login-section">
    <div class="login-card">
        
        <form method="post" action="{{ route('criarUsuario') }}">
            @csrf

            {{-- Ícone de Novo Usuário --}}
            <div class="login-icon">
                <i class="ion-person-add"></i>
            </div>

            <h3 class="mb-4" style="color: #2c3e50; font-weight: 700;">Criar Nova Conta</h3>

            {{-- Exibe erros de validação, se houver --}}
            @if($errors->any())
                <div class="alert alert-danger p-2 text-start" style="font-size: 0.9rem; border-radius: 10px;">
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Campo Nome --}}
            <div class="form-group mb-3">
                <input class="form-control custom-input" 
                       type="text" 
                       name="nome" 
                       placeholder="Nome Completo" 
                       required>
            </div>

            {{-- Campo Login --}}
            <div class="form-group mb-3">
                <input class="form-control custom-input" 
                       type="text" 
                       name="login" 
                       placeholder="Escolha seu Login" 
                       required>
            </div>

            {{-- Campo Telefone --}}
            <div class="form-group mb-3">
                <input class="form-control custom-input" 
                       type="tel" 
                       name="telefone" 
                       placeholder="Telefone (Telegram)" 
                       required>
            </div>

            {{-- Campo Senha --}}
            <div class="form-group mb-4">
                <input class="form-control custom-input" 
                       type="password" 
                       name="password" 
                       placeholder="Crie uma Senha" 
                       required>
            </div>

            {{-- Botão Cadastrar --}}
            <div class="form-group">
                <button class="btn btn-login-submit" type="submit">
                    Cadastrar <i class="ion-android-send ml-2"></i>
                </button>
            </div>

            {{-- Link para voltar ao Login --}}
            <div class="login-links">
                <hr style="width: 50%; margin: 10px auto; opacity: 0.2;">
                
                <span>Já possui cadastro?</span>
                <a href="{{ route('login') }}" class="create-account-link">
                    Fazer Login
                </a>
            </div>

        </form>
    </div>
</div>

{{-- Removemos os scripts antigos daqui pois já estão no layouts.app --}}

@endsection