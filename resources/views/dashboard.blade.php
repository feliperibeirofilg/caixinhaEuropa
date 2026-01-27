@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- BLOCO DE MENSAGENS (Alertas mais suaves) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="ion-checkmark-circled mr-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="ion-alert-circled mr-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    {{-- 1. TOTAL ACUMULADO (Hero Section) --}}
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="balance-card text-center">
                <div class="card-label">Total Arrecadado</div>
                <h2>R$ {{ number_format($valorDepositado, 2, ',', '.') }}</h2>
                <div style="margin-top: 10px; font-size: 0.9rem; opacity: 0.8;">
                    Continue firme rumo à Europa! ✈️
                </div>
            </div>
        </div>
    </div>

    {{-- 2. GRID DE AÇÃO (Coloquei os cards antes da tabela, pois é a ação principal) --}}
    <h3 class="section-title"><i class="ion-cash"></i> Realizar Depósitos</h3>
    
    <div class="row">
        @foreach($listaDeDepositos as $item)
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="deposit-card">
                <div class="card-body text-center">
                    <small class="text-uppercase text-muted font-weight-bold">Caixinha de</small>
                    <div class="deposit-value">R$ {{ number_format($item->valor, 2, ',', '.') }}</div>
                    
                    <div class="deposit-info">
                        <span>Faltam:</span>
                        <span class="badge-count">{{ $item->pendentes }}</span>
                    </div>

                    <form action="{{ route('depositos.pagar', $item->valor) }}" method="POST">
                        @csrf
                        
                        <button type="submit" class="btn-pay" {{ $item->pendentes == 0 ? 'disabled' : '' }}>
                            @if($item->pendentes > 0)
                                Pagar Agora <i class="ion-arrow-right-c ml-1"></i>
                            @else
                                <i class="ion-checkmark-round"></i> Finalizado
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <hr class="my-5" style="opacity: 0.1;">

    {{-- 3. RESUMO DETALHADO (Tabela) --}}
    <h3 class="section-title"><i class="ion-clipboard"></i> Relatório de Progresso</h3>
    
    <div class="table-responsive">
        <table class="table modern-table">
            <thead>
                <tr>
                    <th>Valor do Depósito</th>
                    <th class="text-center">Pendentes (A Pagar)</th>
                    <th class="text-center">Concluídos (Pagos)</th>
                    <th class="text-end">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($listaDeDepositos as $item)
                <tr>
                    <td>
                        <strong style="color: #2c3e50; font-size: 1.1rem;">
                            R$ {{ number_format($item->valor, 2, ',', '.') }}
                        </strong>
                    </td>
                    
                    <td class="text-center">
                        @if($item->pendentes > 0)
                            <span class="badge-count">{{ $item->pendentes }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    <td class="text-center">
                         <span class="badge-done">{{ $item->feitos }}</span>
                    </td>

                    <td class="text-end">
                        @if($item->pendentes == 0)
                            <span style="color: #27ae60;"><i class="ion-checkmark-circled"></i> Completo</span>
                        @else
                            <div class="progress" style="height: 6px; width: 100px; display: inline-flex;">
                                @php
                                    $total = $item->pendentes + $item->feitos;
                                    $porcentagem = $total > 0 ? ($item->feitos / $total) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $porcentagem }}%"></div>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection