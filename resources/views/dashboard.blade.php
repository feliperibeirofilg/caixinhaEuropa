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

    {{-- VISÃO FINANCEIRA GERAL --}}
    <h3 class="section-title"><i class="ion-stats-bars"></i> Visão Financeira</h3>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="deposit-card"><div class="card-body text-center">
                <small class="text-uppercase text-muted">Receitas do mês</small>
                <div class="deposit-value" id="dashReceitas" style="color:#27ae60;">R$ 0,00</div>
            </div></div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="deposit-card"><div class="card-body text-center">
                <small class="text-uppercase text-muted">Despesas do mês</small>
                <div class="deposit-value" id="dashDespesas" style="color:#e74c3c;">R$ 0,00</div>
            </div></div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="deposit-card"><div class="card-body text-center">
                <small class="text-uppercase text-muted">Saldo do mês</small>
                <div class="deposit-value" id="dashSaldo">R$ 0,00</div>
            </div></div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-7 mb-4">
            <div class="chart-card">
                <h5><i class="ion-android-chart"></i> Receitas x Despesas (últimos 6 meses)</h5>
                <canvas id="graficoEvolucao" height="220"></canvas>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="chart-card">
                <h5><i class="ion-pie-graph"></i> Despesas por categoria (mês atual)</h5>
                <canvas id="graficoCategorias" height="220"></canvas>
                <p id="semDespesasCategorias" class="text-muted text-center mt-3 d-none">Sem despesas registradas neste mês.</p>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="chart-card">
                <h5><i class="ion-calendar"></i> Contas mensais ativas</h5>
                <div id="listaContasDashboard" class="small text-muted text-center py-3">Carregando...</div>
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

@section('scripts')
<script>
    const meses = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
    const coresCategorias = ['#27ae60', '#2563eb', '#e74c3c', '#f39c12', '#9b59b6', '#1abc9c', '#e67e22', '#34495e'];

    function carregarVisaoFinanceira() {
        window.apiFetch('/financas').then((financas) => {
            renderizarTilesMes(financas);
            renderizarGraficoEvolucao(financas);
            renderizarGraficoCategorias(financas);
        }).catch(() => {});

        window.apiFetch('/contas-mensais').then((contas) => renderizarContasDashboard(contas)).catch(() => {});
    }

    function renderizarTilesMes(financas) {
        const hoje = new Date();
        const doMes = financas.filter((f) => {
            const data = new Date(f.data_compra + 'T00:00:00');
            return data.getMonth() === hoje.getMonth() && data.getFullYear() === hoje.getFullYear();
        });

        let receitas = 0;
        let despesas = 0;
        doMes.forEach((f) => {
            if (f.tipo === 'receita') receitas += Number(f.valor);
            else despesas += Number(f.valor);
        });

        document.getElementById('dashReceitas').textContent = window.formatarMoeda(receitas);
        document.getElementById('dashDespesas').textContent = window.formatarMoeda(despesas);
        document.getElementById('dashSaldo').textContent = window.formatarMoeda(receitas - despesas);
    }

    function renderizarGraficoEvolucao(financas) {
        const hoje = new Date();
        const labels = [];
        const receitasPorMes = [];
        const despesasPorMes = [];

        for (let i = 5; i >= 0; i--) {
            const referencia = new Date(hoje.getFullYear(), hoje.getMonth() - i, 1);
            labels.push(`${meses[referencia.getMonth()]}/${String(referencia.getFullYear()).slice(2)}`);

            const doMes = financas.filter((f) => {
                const data = new Date(f.data_compra + 'T00:00:00');
                return data.getMonth() === referencia.getMonth() && data.getFullYear() === referencia.getFullYear();
            });

            receitasPorMes.push(doMes.filter((f) => f.tipo === 'receita').reduce((s, f) => s + Number(f.valor), 0));
            despesasPorMes.push(doMes.filter((f) => f.tipo === 'despesa').reduce((s, f) => s + Number(f.valor), 0));
        }

        new Chart(document.getElementById('graficoEvolucao'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Receitas', data: receitasPorMes, backgroundColor: '#27ae60', borderRadius: 6 },
                    { label: 'Despesas', data: despesasPorMes, backgroundColor: '#e74c3c', borderRadius: 6 },
                ],
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true } },
            },
        });
    }

    function renderizarGraficoCategorias(financas) {
        const hoje = new Date();
        const despesasDoMes = financas.filter((f) => {
            const data = new Date(f.data_compra + 'T00:00:00');
            return f.tipo === 'despesa' && data.getMonth() === hoje.getMonth() && data.getFullYear() === hoje.getFullYear();
        });

        if (!despesasDoMes.length) {
            document.getElementById('semDespesasCategorias').classList.remove('d-none');
            return;
        }

        const totaisPorCategoria = {};
        despesasDoMes.forEach((f) => {
            const nome = f.categoria ? f.categoria.nome : 'Sem categoria';
            totaisPorCategoria[nome] = (totaisPorCategoria[nome] || 0) + Number(f.valor);
        });

        new Chart(document.getElementById('graficoCategorias'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(totaisPorCategoria),
                datasets: [{ data: Object.values(totaisPorCategoria), backgroundColor: coresCategorias }],
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
            },
        });
    }

    function renderizarContasDashboard(contas) {
        const alvo = document.getElementById('listaContasDashboard');
        const ativas = contas.filter((c) => c.ativa);

        if (!ativas.length) {
            alvo.innerHTML = '<p class="text-muted">Nenhuma conta mensal cadastrada.</p>';
            return;
        }

        alvo.innerHTML = `<div class="table-responsive"><table class="table modern-table mb-0">
            <thead><tr><th>Nome</th><th>Categoria</th><th class="text-center">Vencimento</th><th class="text-end">Valor estimado</th></tr></thead>
            <tbody>
                ${ativas.sort((a, b) => a.dia_vencimento - b.dia_vencimento).map((c) => `
                    <tr>
                        <td>${c.nome}</td>
                        <td>${c.categoria ? c.categoria.nome : '-'}</td>
                        <td class="text-center">Dia ${c.dia_vencimento}</td>
                        <td class="text-end">${c.valor_estimado ? window.formatarMoeda(c.valor_estimado) : '-'}</td>
                    </tr>
                `).join('')}
            </tbody>
        </table></div>`;
    }

    carregarVisaoFinanceira();
</script>
@endsection