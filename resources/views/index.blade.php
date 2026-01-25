@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Bem-vindo à Caixinha Europa</div>

                <h1>Escolha o valor que você quer economisar:</h1>
                <div class="card-body">
                    <ul>
                        <li><a href="{{ route('caixinha.escolha', ['valor' => 1000]) }}">Caixinha de R$ 1.000,00</a></li>
                        <li><a href="{{ route('caixinha.escolha', ['valor' => 5000]) }}">Caixinha de R$ 5.000,00</a></li>
                        <li><a href="{{ route('caixinha.escolha', ['valor' => 10000]) }}">Caixinha de R$ 10.000,00</a></li>
                    </ul>
            </div>
        </div>
    </div>


@endsection