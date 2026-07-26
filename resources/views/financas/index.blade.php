@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="section-title mb-0"><i class="ion-cash"></i> Lançamentos</h3>
        <button type="button" class="btn-pay w-auto px-4" data-bs-toggle="modal" data-bs-target="#modalFinanca" onclick="abrirNovoLancamento()">
            <i class="ion-plus"></i> Novo Lançamento
        </button>
    </div>

    {{-- Filtros --}}
    <div class="deposit-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-6 col-md-2">
                    <label class="form-label small text-muted mb-1">Mês</label>
                    <select class="finance-input" id="filtro_mes"></select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small text-muted mb-1">Ano</label>
                    <select class="finance-input" id="filtro_ano"></select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small text-muted mb-1">Categoria</label>
                    <select class="finance-input" id="filtro_categoria">
                        <option value="">Todas</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small text-muted mb-1">Cartão</label>
                    <select class="finance-input" id="filtro_cartao">
                        <option value="">Todos</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <button class="btn-pay" onclick="carregarFinancas()"><i class="ion-funnel"></i> Filtrar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="alertaFinancas"></div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="deposit-card"><div class="card-body text-center">
                <small class="text-uppercase text-muted">Receitas</small>
                <div class="deposit-value" id="totalReceitas" style="color:#27ae60;">R$ 0,00</div>
            </div></div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="deposit-card"><div class="card-body text-center">
                <small class="text-uppercase text-muted">Despesas</small>
                <div class="deposit-value" id="totalDespesas" style="color:#e74c3c;">R$ 0,00</div>
            </div></div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="deposit-card"><div class="card-body text-center">
                <small class="text-uppercase text-muted">Saldo</small>
                <div class="deposit-value" id="totalSaldo">R$ 0,00</div>
            </div></div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table align-middle">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Forma</th>
                    <th class="text-end">Valor</th>
                    <th class="text-center">Data</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaFinancas">
                <tr><td colspan="7" class="text-center text-muted py-4">Carregando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Criar/Editar Lançamento --}}
<div class="modal fade" id="modalFinanca" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px;">
            <form id="formFinanca">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalFinanca">Novo Lançamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="financa_id">

                    <div class="finance-form-group">
                        <label>Descrição</label>
                        <input type="text" class="finance-input" id="financa_descricao" required maxlength="255">
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Valor</label>
                                <input type="number" step="0.01" min="0" class="finance-input" id="financa_valor" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Tipo</label>
                                <select class="finance-input" id="financa_tipo" required>
                                    <option value="despesa">Despesa</option>
                                    <option value="receita">Receita</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Forma de Pagamento</label>
                                <select class="finance-input" id="financa_forma_pagamento" required>
                                    <option value="pix">Pix</option>
                                    <option value="debito">Débito</option>
                                    <option value="credito">Crédito</option>
                                    <option value="dinheiro">Dinheiro</option>
                                    <option value="boleto">Boleto</option>
                                    <option value="transferencia">Transferência</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Data</label>
                                <input type="date" class="finance-input" id="financa_data_compra" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Categoria</label>
                                <select class="finance-input" id="financa_categoria_id">
                                    <option value="">Nenhuma</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Cartão</label>
                                <select class="finance-input" id="financa_cartao_id">
                                    <option value="">Nenhum</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="finance-form-group">
                                <label>Parcelas</label>
                                <input type="number" min="1" class="finance-input" id="financa_parcelas" value="1">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="finance-form-group">
                                <label>Parcela Atual</label>
                                <input type="number" min="1" class="finance-input" id="financa_parcela_atual" value="1">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="finance-form-group">
                                <label>Status</label>
                                <select class="finance-input" id="financa_status">
                                    <option value="pago">Pago</option>
                                    <option value="pendente">Pendente</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="financa_recorrente">
                        <label class="form-check-label" for="financa_recorrente">Lançamento recorrente</label>
                    </div>

                    <div class="finance-form-group">
                        <label>Observação</label>
                        <textarea class="finance-input" id="financa_observacao" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-pay w-auto px-4">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const modalFinanca = new bootstrap.Modal(document.getElementById('modalFinanca'));
    let financas = [];
    let categoriasDisponiveis = [];
    let cartoesDisponiveis = [];

    const meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];

    function popularFiltrosData() {
        const seletorMes = document.getElementById('filtro_mes');
        const hoje = new Date();
        seletorMes.innerHTML = '<option value="">Todos</option>' + meses.map((nome, i) => `<option value="${i + 1}" ${i + 1 === hoje.getMonth() + 1 ? 'selected' : ''}>${nome}</option>`).join('');

        const seletorAno = document.getElementById('filtro_ano');
        const anoAtual = hoje.getFullYear();
        let anosHtml = '<option value="">Todos</option>';
        for (let ano = anoAtual; ano >= anoAtual - 4; ano--) {
            anosHtml += `<option value="${ano}" ${ano === anoAtual ? 'selected' : ''}>${ano}</option>`;
        }
        seletorAno.innerHTML = anosHtml;
    }

    function mostrarAlerta(msg, tipo = 'success') {
        const alvo = document.getElementById('alertaFinancas');
        alvo.innerHTML = `<div class="alert alert-${tipo === 'success' ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
            ${msg}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    }

    function carregarListasApoio() {
        return Promise.all([
            window.apiFetch('/categorias'),
            window.apiFetch('/cartoes'),
        ]).then(([categorias, cartoes]) => {
            categoriasDisponiveis = categorias;
            cartoesDisponiveis = cartoes;

            document.getElementById('filtro_categoria').innerHTML = '<option value="">Todas</option>' +
                categorias.map((c) => `<option value="${c.id}">${c.nome}</option>`).join('');
            document.getElementById('filtro_cartao').innerHTML = '<option value="">Todos</option>' +
                cartoes.map((c) => `<option value="${c.id}">${c.nome}</option>`).join('');

            document.getElementById('financa_categoria_id').innerHTML = '<option value="">Nenhuma</option>' +
                categorias.map((c) => `<option value="${c.id}">${c.nome} (${c.tipo})</option>`).join('');
            document.getElementById('financa_cartao_id').innerHTML = '<option value="">Nenhum</option>' +
                cartoes.map((c) => `<option value="${c.id}">${c.nome}</option>`).join('');
        });
    }

    function carregarFinancas() {
        const params = new URLSearchParams();
        const mes = document.getElementById('filtro_mes').value;
        const ano = document.getElementById('filtro_ano').value;
        const categoriaId = document.getElementById('filtro_categoria').value;
        const cartaoId = document.getElementById('filtro_cartao').value;

        if (mes) params.set('mes', mes);
        if (ano) params.set('ano', ano);
        if (categoriaId) params.set('categoria_id', categoriaId);
        if (cartaoId) params.set('cartao_id', cartaoId);

        window.apiFetch(`/financas?${params.toString()}`).then((dados) => {
            financas = dados;
            renderizarFinancas();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    }

    function renderizarFinancas() {
        const corpo = document.getElementById('tabelaFinancas');

        let receitas = 0;
        let despesas = 0;
        financas.forEach((f) => {
            if (f.tipo === 'receita') receitas += Number(f.valor);
            else despesas += Number(f.valor);
        });

        document.getElementById('totalReceitas').textContent = window.formatarMoeda(receitas);
        document.getElementById('totalDespesas').textContent = window.formatarMoeda(despesas);
        document.getElementById('totalSaldo').textContent = window.formatarMoeda(receitas - despesas);

        if (!financas.length) {
            corpo.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Nenhum lançamento encontrado para o filtro selecionado.</td></tr>';
            return;
        }

        corpo.innerHTML = financas.map((financa) => `
            <tr>
                <td>
                    <strong>${financa.descricao}</strong>
                    ${financa.parcelas > 1 ? `<div class="text-muted small">Parcela ${financa.parcela_atual}/${financa.parcelas}</div>` : ''}
                </td>
                <td>${financa.categoria ? financa.categoria.nome : '-'}</td>
                <td class="text-capitalize">${financa.forma_pagamento}</td>
                <td class="text-end" style="color:${financa.tipo === 'receita' ? '#27ae60' : '#e74c3c'};font-weight:600;">
                    ${financa.tipo === 'receita' ? '+' : '-'} ${window.formatarMoeda(financa.valor)}
                </td>
                <td class="text-center">${new Date(financa.data_compra + 'T00:00:00').toLocaleDateString('pt-BR')}</td>
                <td class="text-center"><span class="${financa.status === 'pago' ? 'badge-done' : 'badge-count'}">${financa.status}</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-secondary" onclick="editarFinanca(${financa.id})"><i class="ion-edit"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="excluirFinanca(${financa.id})"><i class="ion-trash-a"></i></button>
                </td>
            </tr>
        `).join('');
    }

    function abrirNovoLancamento() {
        document.getElementById('formFinanca').reset();
        document.getElementById('financa_id').value = '';
        document.getElementById('financa_data_compra').value = new Date().toISOString().slice(0, 10);
        document.getElementById('financa_parcelas').value = 1;
        document.getElementById('financa_parcela_atual').value = 1;
        document.getElementById('financa_status').value = 'pago';
        document.getElementById('tituloModalFinanca').textContent = 'Novo Lançamento';
    }

    function editarFinanca(id) {
        const financa = financas.find((f) => f.id === id);
        if (!financa) return;

        document.getElementById('financa_id').value = financa.id;
        document.getElementById('financa_descricao').value = financa.descricao;
        document.getElementById('financa_valor').value = financa.valor;
        document.getElementById('financa_tipo').value = financa.tipo;
        document.getElementById('financa_forma_pagamento').value = financa.forma_pagamento;
        document.getElementById('financa_data_compra').value = financa.data_compra;
        document.getElementById('financa_categoria_id').value = financa.categoria_id || '';
        document.getElementById('financa_cartao_id').value = financa.cartao_id || '';
        document.getElementById('financa_parcelas').value = financa.parcelas;
        document.getElementById('financa_parcela_atual').value = financa.parcela_atual;
        document.getElementById('financa_status').value = financa.status;
        document.getElementById('financa_recorrente').checked = !!financa.recorrente;
        document.getElementById('financa_observacao').value = financa.observacao || '';
        document.getElementById('tituloModalFinanca').textContent = 'Editar Lançamento';
        modalFinanca.show();
    }

    function excluirFinanca(id) {
        if (!confirm('Tem certeza que deseja excluir este lançamento?')) return;

        window.apiFetch(`/financas/${id}`, { method: 'DELETE' }).then(() => {
            mostrarAlerta('Lançamento excluído com sucesso!');
            carregarFinancas();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    }

    document.getElementById('formFinanca').addEventListener('submit', function (event) {
        event.preventDefault();

        const id = document.getElementById('financa_id').value;
        const payload = {
            descricao: document.getElementById('financa_descricao').value,
            valor: document.getElementById('financa_valor').value,
            tipo: document.getElementById('financa_tipo').value,
            forma_pagamento: document.getElementById('financa_forma_pagamento').value,
            data_compra: document.getElementById('financa_data_compra').value,
            categoria_id: document.getElementById('financa_categoria_id').value || null,
            cartao_id: document.getElementById('financa_cartao_id').value || null,
            parcelas: document.getElementById('financa_parcelas').value || 1,
            parcela_atual: document.getElementById('financa_parcela_atual').value || 1,
            status: document.getElementById('financa_status').value,
            recorrente: document.getElementById('financa_recorrente').checked,
            observacao: document.getElementById('financa_observacao').value || null,
        };

        const url = id ? `/financas/${id}` : '/financas';
        const method = id ? 'PUT' : 'POST';

        window.apiFetch(url, { method, body: JSON.stringify(payload) }).then(() => {
            modalFinanca.hide();
            mostrarAlerta('Lançamento salvo com sucesso!');
            carregarFinancas();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    });

    popularFiltrosData();
    carregarListasApoio().then(carregarFinancas);
</script>
@endsection
