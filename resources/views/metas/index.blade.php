@extends('layouts.app')

@section('content')

<div class="container py-5">
    
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="page-title">Bem-vindo ao Caixinha Europa ✈️</h1>
            <p class="page-subtitle">Escolha sua meta financeira e comece a juntar dinheiro hoje mesmo!</p>
        </div>
    </div>

    <div class="row justify-content-center">

        <div class="col-md-4 mb-4">
            <div class="option-card">
                <div>
                    <span class="level-badge">Iniciante</span>
                    <div class="option-value"><small>R$</small> 1.000</div>
                    <p class="option-desc">Ideal para começar a criar o hábito de poupar. Um passo de cada vez!</p>
                </div>
                <a href="{{ route('caixinha.escolha', ['valor' => 1000]) }}" class="btn-choose">
                    Selecionar Meta
                </a>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="option-card card-highlight">
                <div>
                    <span class="level-badge" style="background: #eaf2f8; color: #3498db;">Intermediário</span>
                    <div class="option-value" style="color: #3498db;"><small>R$</small> 5.000</div>
                    <p class="option-desc">Uma ótima reserva para sua viagem. Exige disciplina e constância.</p>
                </div>
                <a href="{{ route('caixinha.escolha', ['valor' => 5000]) }}" class="btn-choose" style="background-color: #3498db;">
                    Selecionar Meta
                </a>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="option-card">
                <div>
                    <span class="level-badge" style="background: #f9ebea; color: #e74c3c;">Avançado</span>
                    <div class="option-value" style="color: #c0392b;"><small>R$</small> 10.000</div>
                    <p class="option-desc">Para quem quer garantir a Eurotrip completa! Desafio máximo.</p>
                </div>
                <a href="{{ route('caixinha.escolha', ['valor' => 10000]) }}" class="btn-choose" style="background-color: #e74c3c;">
                    Selecionar Meta
                </a>
            </div>
        </div>

    </div>

    <div class="row justify-content-center mt-2">
        <div class="col-md-8">
            <div class="option-card" style="border-style:dashed;">
                <div>
                    <span class="level-badge" style="background:#f4f0fb;color:#8e44ad;">Personalizada</span>
                    <div class="option-value" style="color:#8e44ad;font-size:1.6rem;">Crie a sua própria meta</div>
                    <p class="option-desc">Escolha o nome, o valor e monte você mesmo a distribuição dos depósitos.</p>
                </div>
                <a href="{{ route('caixinha.personalizada.form') }}" class="btn-choose" style="background-color:#8e44ad;">
                    Criar Meta Personalizada
                </a>
            </div>
        </div>
    </div>
</div>

@endsection