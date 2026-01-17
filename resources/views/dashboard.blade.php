@extends('layouts.app')
@section('content')

    <h1>Você está logado</h1>

    <!-- Sair do sistema -->
    <form method="post" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Sair</button>
    </form>
    
@endsection