@extends('layouts.app')
@section('content')

<div class="login-dark">
        <form method="post" action="{{route('criarUsuario') }}">
            @csrf
            <h2 class="sr-only">Criar Usuario</h2>
            <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
            <div class="form-group">
                <input class="form-control" type="nome" name="nome" placeholder="Nome">
            </div>
            <div class="form-group">
                <input class="form-control" type="login" name="login" placeholder="Login">
            </div>
            <div class="form-group">
                <input class="form-control" type="telefone" name="telefone" placeholder="Telegram">
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit">
                    Criar Usuario
                </button>
            </div><a href="#" class="forgot">Esqueceu seu email ou senha?</a>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
@endsection