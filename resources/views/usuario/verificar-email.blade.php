@extends('layouts.app')

@section('content')

<div class="container login-section">
    <div class="login-card">

        <form method="post" action="{{ route('usuario.verificar-email.confirmar') }}">
            @csrf

            <div class="login-icon">
                <i class="ion-email"></i>
            </div>

            <h3 class="mb-4" style="color: #2c3e50; font-weight: 700;">Verifique seu Email</h3>

            <p class="mb-4" style="color: #6c757d;">
                Enviamos um código de 5 dígitos para o seu email. Digite-o abaixo para ativar sua conta.
            </p>

            @if($errors->any())
                <div class="alert alert-danger p-2" style="font-size: 0.9rem; border-radius: 10px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="form-group mb-4">
                <input class="form-control custom-input text-center"
                       type="text"
                       name="token"
                       placeholder="00000"
                       inputmode="numeric"
                       maxlength="5"
                       style="font-size: 1.5rem; letter-spacing: 8px;"
                       required
                       autofocus>
            </div>

            <button class="btn btn-login-submit" type="submit">
                Confirmar <i class="ion-checkmark ml-2"></i>
            </button>

        </form>
    </div>
</div>

@endsection
