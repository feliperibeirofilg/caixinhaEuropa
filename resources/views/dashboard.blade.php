@extends('layouts.app')

@section('content')

<div class="container">

    {{-- BLOCO DE MENSAGENS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif


    {{-- BLOCO DE TOTAL GERAL (Melhor visualizado em Card do que tabela) --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Já Arrecadado</div>
                <div class="card-body">
                    <h2 class="card-title">
                        R$ {{ number_format($valorDepositado, 2, ',', '.') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    {{-- TABELA DE RESUMO --}}
    <h3 class="mt-4">Resumo da Tabela</h3>
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Valor do Depósito</th>
                <th>Pendentes (Faltam)</th>
                <th>Realizados (Feitos)</th>
            </tr>
        </thead>
        <tbody>
            {{-- ATENÇÃO: Verifique se no Controller está enviando 'listaDeDepositos' --}}
            @foreach($listaDeDepositos as $item)
            <tr>
                <td><strong>R$ {{ number_format($item->valor, 2, ',', '.') }}</strong></td>
                
                {{-- Coluna Pendentes --}}
                <td style="color: red; font-weight: bold;">
                    {{ $item->pendentes }}
                </td>

                {{-- Coluna Feitos --}}
                <td style="color: green; font-weight: bold;">
                    {{ $item->feitos }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    {{-- CARDS COM BOTÕES DE AÇÃO --}}
    <h3 class="mt-4">Realizar Depósitos</h3>
    
    <div class="row">
        @foreach($listaDeDepositos as $item)
        <div class="col-md-4 mb-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-primary">R$ {{ number_format($item->valor, 2, ',', '.') }}</h3>
                    
                    {{-- Aqui usamos 'pendentes' que vem do SQL --}}
                    <p class="card-text">
                        Restantes: <span class="badge badge-warning" style="font-size: 1rem;">{{ $item->pendentes }}</span>
                    </p>

                    <form action="{{ route('depositos.pagar', $item->valor) }}" method="POST">
                        @csrf
                        
                        {{-- O botão desabilita se pendentes for igual a ZERO --}}
                        <button type="submit" class="btn btn-success btn-block" {{ $item->pendentes == 0 ? 'disabled' : '' }}>
                            @if($item->pendentes > 0)
                                Pagar R$ {{ number_format($item->valor, 2, ',', '.') }}
                            @else
                                Concluído!
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

@endsection