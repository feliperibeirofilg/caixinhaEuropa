@extends('layouts.app')

@section('content')

<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h1 class="page-title" style="margin-bottom:10px;">Olá, {{ Auth::user()->nome }} 👋</h1>
    <p class="page-subtitle" style="margin-bottom:30px;">O que você quer registrar agora?</p>

    <div id="alertaInicio"></div>

    {{-- AÇÕES RÁPIDAS --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-5 mb-4">
            <button type="button" class="quick-action-card w-100" data-bs-toggle="modal" data-bs-target="#modalGasto">
                <div class="quick-action-icon" style="background:#fdf2f2;color:#e74c3c;"><i class="ion-card"></i></div>
                <div class="quick-action-title">Registrar Gasto</div>
                <div class="quick-action-desc">Uma compra no cartão, pix ou dinheiro</div>
            </button>
        </div>
        <div class="col-md-5 mb-4">
            <button type="button" class="quick-action-card w-100" data-bs-toggle="modal" data-bs-target="#modalSalario">
                <div class="quick-action-icon" style="background:#eafaf1;color:#27ae60;"><i class="ion-cash"></i></div>
                <div class="quick-action-title">Registrar Salário</div>
                <div class="quick-action-desc">Lançar o recebimento do mês</div>
            </button>
        </div>
    </div>

    {{-- RESUMO DO MÊS --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="deposit-card"><div class="card-body text-center">
                <small class="text-uppercase text-muted">Receitas do mês</small>
                <div class="deposit-value" id="resumoReceitas" style="color:#27ae60;">R$ 0,00</div>
            </div></div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="deposit-card"><div class="card-body text-center">
                <small class="text-uppercase text-muted">Despesas do mês</small>
                <div class="deposit-value" id="resumoDespesas" style="color:#e74c3c;">R$ 0,00</div>
            </div></div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="deposit-card"><div class="card-body text-center">
                <small class="text-uppercase text-muted">Saldo do mês</small>
                <div class="deposit-value" id="resumoSaldo">R$ 0,00</div>
            </div></div>
        </div>
    </div>

    {{-- ÚLTIMOS LANÇAMENTOS --}}
    <h3 class="section-title"><i class="ion-clock"></i> Últimos lançamentos</h3>
    <div class="table-responsive mb-4">
        <table class="table modern-table align-middle">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th class="text-end">Valor</th>
                    <th class="text-center">Data</th>
                </tr>
            </thead>
            <tbody id="tabelaUltimosLancamentos">
                <tr><td colspan="3" class="text-center text-muted py-4">Carregando...</td></tr>
            </tbody>
        </table>
    </div>

    {{-- ATALHOS PARA O RESTO DO SISTEMA --}}
    <div class="d-flex flex-wrap gap-2 justify-content-center mb-4">
        <a href="{{ route('painel.financas') }}" class="btn btn-outline-secondary btn-sm">Ver todos os lançamentos</a>
        <a href="{{ route('painel.cartoes') }}" class="btn btn-outline-secondary btn-sm">Cartões</a>
        <a href="{{ route('painel.contas-mensais') }}" class="btn btn-outline-secondary btn-sm">Contas Mensais</a>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">Dashboard completo</a>
    </div>
</div>

{{-- Modal Registrar Gasto --}}
<div class="modal fade" id="modalGasto" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px;">
            <form id="formGasto">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Gasto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="finance-form-group">
                        <label>Descrição</label>
                        <input type="text" class="finance-input" id="gasto_descricao" placeholder="Ex: Mercado, Uber..." required maxlength="255">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Valor</label>
                                <input type="number" step="0.01" min="0" class="finance-input" id="gasto_valor" required autofocus>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Data</label>
                                <input type="date" class="finance-input" id="gasto_data" required>
                            </div>
                        </div>
                    </div>
                    <div class="finance-form-group">
                        <label>Cartão (opcional)</label>
                        <select class="finance-input" id="gasto_cartao_id">
                            <option value="">À vista / Pix</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-pay w-auto px-4">Salvar Gasto</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Registrar Salário --}}
<div class="modal fade" id="modalSalario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px;">
            <form id="formSalario">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Salário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="finance-form-group">
                        <label>Valor</label>
                        <input type="number" step="0.01" min="0" class="finance-input" id="salario_valor" required autofocus>
                    </div>
                    <div class="finance-form-group">
                        <label>Data de recebimento</label>
                        <input type="date" class="finance-input" id="salario_data" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-pay w-auto px-4">Salvar Salário</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const modalGasto = new bootstrap.Modal(document.getElementById('modalGasto'));
    const modalSalario = new bootstrap.Modal(document.getElementById('modalSalario'));
    let cartoesDisponiveis = [];
    let categoriaSalarioId = null;

    function mostrarAlerta(msg, tipo = 'success') {
        const alvo = document.getElementById('alertaInicio');
        alvo.innerHTML = `<div class="alert alert-${tipo === 'success' ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
            ${msg}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    }

    function hoje() {
        return new Date().toISOString().slice(0, 10);
    }

    function carregarCartoes() {
        window.apiFetch('/cartoes').then((cartoes) => {
            cartoesDisponiveis = cartoes;
            document.getElementById('gasto_cartao_id').innerHTML = '<option value="">À vista / Pix</option>' +
                cartoes.map((c) => `<option value="${c.id}" data-tipo="${c.tipo}">${c.nome}</option>`).join('');
        }).catch(() => {});
    }

    function carregarResumoEUltimos() {
        window.apiFetch('/financas').then((financas) => {
            const hojeData = new Date();
            const doMes = financas.filter((f) => {
                const data = new Date(f.data_compra + 'T00:00:00');
                return data.getMonth() === hojeData.getMonth() && data.getFullYear() === hojeData.getFullYear();
            });

            let receitas = 0;
            let despesas = 0;
            doMes.forEach((f) => {
                if (f.tipo === 'receita') receitas += Number(f.valor);
                else despesas += Number(f.valor);
            });

            document.getElementById('resumoReceitas').textContent = window.formatarMoeda(receitas);
            document.getElementById('resumoDespesas').textContent = window.formatarMoeda(despesas);
            document.getElementById('resumoSaldo').textContent = window.formatarMoeda(receitas - despesas);

            const ultimos = [...financas]
                .sort((a, b) => new Date(b.data_compra) - new Date(a.data_compra) || b.id - a.id)
                .slice(0, 5);

            const corpo = document.getElementById('tabelaUltimosLancamentos');
            if (!ultimos.length) {
                corpo.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-4">Nenhum lançamento ainda. Use os atalhos acima para começar!</td></tr>';
                return;
            }

            corpo.innerHTML = ultimos.map((f) => `
                <tr>
                    <td>${f.descricao}</td>
                    <td class="text-end" style="color:${f.tipo === 'receita' ? '#27ae60' : '#e74c3c'};font-weight:600;">
                        ${f.tipo === 'receita' ? '+' : '-'} ${window.formatarMoeda(f.valor)}
                    </td>
                    <td class="text-center">${new Date(f.data_compra + 'T00:00:00').toLocaleDateString('pt-BR')}</td>
                </tr>
            `).join('');
        }).catch(() => {});
    }

    document.getElementById('gasto_data').value = hoje();
    document.getElementById('salario_data').value = hoje();

    document.getElementById('formGasto').addEventListener('submit', function (event) {
        event.preventDefault();

        const cartaoSelect = document.getElementById('gasto_cartao_id');
        const cartaoId = cartaoSelect.value || null;
        const tipoCartao = cartaoId ? cartaoSelect.selectedOptions[0].dataset.tipo : null;

        const payload = {
            descricao: document.getElementById('gasto_descricao').value,
            valor: document.getElementById('gasto_valor').value,
            tipo: 'despesa',
            forma_pagamento: cartaoId ? tipoCartao : 'pix',
            data_compra: document.getElementById('gasto_data').value,
            cartao_id: cartaoId,
            status: 'pago',
        };

        window.apiFetch('/financas', { method: 'POST', body: JSON.stringify(payload) }).then(() => {
            modalGasto.hide();
            document.getElementById('formGasto').reset();
            document.getElementById('gasto_data').value = hoje();
            mostrarAlerta('Gasto registrado com sucesso!');
            carregarResumoEUltimos();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    });

    function obterCategoriaSalario() {
        if (categoriaSalarioId) return Promise.resolve(categoriaSalarioId);

        return window.apiFetch('/categorias').then((categorias) => {
            const existente = categorias.find((c) => c.nome === 'Salário' && c.tipo === 'receita');
            if (existente) {
                categoriaSalarioId = existente.id;
                return categoriaSalarioId;
            }

            return window.apiFetch('/categorias', {
                method: 'POST',
                body: JSON.stringify({ nome: 'Salário', tipo: 'receita', cor: '#27ae60', icone: 'ion-cash' }),
            }).then((nova) => {
                categoriaSalarioId = nova.id;
                return categoriaSalarioId;
            });
        });
    }

    document.getElementById('formSalario').addEventListener('submit', function (event) {
        event.preventDefault();

        const valor = document.getElementById('salario_valor').value;
        const data = document.getElementById('salario_data').value;

        obterCategoriaSalario().then((categoriaId) => {
            const payload = {
                descricao: 'Salário',
                valor,
                tipo: 'receita',
                forma_pagamento: 'transferencia',
                data_compra: data,
                categoria_id: categoriaId,
                recorrente: true,
                status: 'pago',
            };

            return window.apiFetch('/financas', { method: 'POST', body: JSON.stringify(payload) });
        }).then(() => {
            modalSalario.hide();
            document.getElementById('formSalario').reset();
            document.getElementById('salario_data').value = hoje();
            mostrarAlerta('Salário registrado com sucesso!');
            carregarResumoEUltimos();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    });

    carregarCartoes();
    carregarResumoEUltimos();
</script>
@endsection
