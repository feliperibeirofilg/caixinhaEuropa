@extends('layouts.app')

@section('content')

<style>
    /* Estilos Modernos para o Login Financeiro */
    .finance-login-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 100px); /* Ajuste se tiver navbar */
        background-color: #f8fafc; /* Fundo cinza bem claro/azulado */
        padding: 20px;
    }
    .finance-login-card {
        background: #ffffff;
        width: 100%;
        max-width: 420px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05); /* Sombra suave */
        padding: 40px;
    }
    .finance-login-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .finance-login-header .icon-bg {
        background: #eff6ff; /* Azul clarinho */
        color: #2563eb; /* Azul confiança */
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin: 0 auto 16px auto;
    }
    .finance-login-header h3 {
        color: #0f172a;
        font-weight: 700;
        font-size: 1.5rem;
        margin: 0;
    }
    .finance-login-header p {
        color: #64748b;
        font-size: 0.95rem;
        margin-top: 5px;
    }
    .finance-form-group {
        margin-bottom: 20px;
        text-align: left;
    }
    .finance-form-group label {
        display: block;
        color: #334155;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 8px;
    }
    .finance-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        color: #1e293b;
        background-color: #f8fafc;
        transition: all 0.2s ease-in-out;
    }
    .finance-input:focus {
        outline: none;
        border-color: #2563eb;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    .finance-btn-submit {
        width: 100%;
        background: #2563eb;
        color: #ffffff;
        border: none;
        padding: 14px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s ease;
        margin-top: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }
    .finance-btn-submit:hover {
        background: #1d4ed8;
    }
    .finance-divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: #94a3b8;
        font-size: 0.85rem;
        margin: 25px 0;
    }
    .finance-divider::before, .finance-divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e2e8f0;
    }
    .finance-divider:not(:empty)::before { margin-right: .5em; }
    .finance-divider:not(:empty)::after { margin-left: .5em; }
    
    .finance-links {
        text-align: center;
        font-size: 0.9rem;
    }
    .finance-links a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }
    .finance-links a:hover {
        text-decoration: underline;
    }
    .finance-alert {
        background-color: #fef2f2;
        color: #b91c1c;
        border: 1px solid #f87171;
        border-radius: 10px;
        padding: 12px;
        font-size: 0.9rem;
        margin-bottom: 20px;
        text-align: center;
    }
</style>

<div class="finance-login-wrapper">
    <div class="finance-login-card">
        
        <div class="finance-login-header">
            {{-- Ícone com fundo arredondado --}}
            <div class="icon-bg">
                <i class="ion-ios-locked"></i>
            </div>
            <h3>Acesse sua conta</h3>
            <p>Gerencie suas finanças de forma simples.</p>
        </div>

        {{-- Mensagens de Erro --}}
        @if($errors->any())
            <div class="finance-alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="post" action="{{ route('autenticar') }}">
            @csrf

            {{-- Campo E-mail --}}
            <div class="finance-form-group">
                <label for="email">E-mail</label>
                <input class="finance-input" 
                       type="email" 
                       id="email"
                       name="email" 
                       placeholder="exemplo@email.com" 
                       value="{{ old('email') }}"
                       required 
                       autofocus>
            </div>

            {{-- Campo Senha --}}
            <div class="finance-form-group">
                <label for="password">Senha</label>
                <input class="finance-input" 
                       type="password" 
                       id="password"
                       name="password" 
                       placeholder="••••••••" 
                       required>
            </div>

            <div style="text-align: right; margin-bottom: 20px;">
                <a href="#" style="font-size: 0.85rem; color: #2563eb; text-decoration: none;">Esqueceu sua senha?</a>
            </div>

            {{-- Botão --}}
            <button class="finance-btn-submit" type="submit">
                Entrar <i class="ion-log-in"></i>
            </button>

            <div class="finance-divider">Novo por aqui?</div>

            {{-- Link para Criar Conta --}}
            <div class="finance-links">
                <a href="{{ route('criarUsuario') }}">
                    Criar minha conta agora
                </a>
            </div>

        </form>
    </div>
</div>

@endsection