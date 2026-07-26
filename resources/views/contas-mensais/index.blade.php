@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="section-title mb-0"><i class="ion-calendar"></i> Contas Mensais</h3>
        <button type="button" class="btn-pay w-auto px-4" data-bs-toggle="modal" data-bs-target="#modalConta" onclick="abrirNovaConta()">
            <i class="ion-plus"></i> Nova Conta
        </button>
    </div>

    <div id="alertaContas"></div>

    <div class="row" id="listaContas">
        <p class="text-center text-muted py-4">Carregando...</p>
    </div>
</div>

{{-- Modal Criar/Editar Conta Mensal --}}
<div class="modal fade" id="modalConta" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px;">
            <form id="formConta">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalConta">Nova Conta Mensal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="conta_id">

                    <div class="finance-form-group">
                        <label>Nome</label>
                        <input type="text" class="finance-input" id="conta_nome" required maxlength="255">
                    </div>

                    <div class="finance-form-group">
                        <label>Categoria</label>
                        <select class="finance-input" id="conta_categoria_id">
                            <option value="">Nenhuma</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Valor Estimado</label>
                                <input type="number" step="0.01" min="0" class="finance-input" id="conta_valor_estimado">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Dia de Vencimento</label>
                                <input type="number" min="1" max="31" class="finance-input" id="conta_dia_vencimento" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Tipo</label>
                                <select class="finance-input" id="conta_tipo">
                                    <option value="fixa">Fixa</option>
                                    <option value="variavel">Variável</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6 d-flex align-items-end">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="conta_ativa" checked>
                                <label class="form-check-label" for="conta_ativa">Conta ativa</label>
                            </div>
                        </div>
                    </div>

                    <div class="finance-form-group">
                        <label>Observação</label>
                        <textarea class="finance-input" id="conta_observacao" rows="2"></textarea>
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

{{-- Modal Registrar Pagamento --}}
<div class="modal fade" id="modalPagamentoConta" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px;">
            <form id="formPagamentoConta">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Pagamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="pgc_conta_id">

                    <div class="row">
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Mês de competência</label>
                                <input type="number" min="1" max="12" class="finance-input" id="pgc_mes" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Ano de competência</label>
                                <input type="number" min="2000" class="finance-input" id="pgc_ano" required>
                            </div>
                        </div>
                    </div>

                    <div class="finance-form-group">
                        <label>Valor pago</label>
                        <input type="number" step="0.01" min="0" class="finance-input" id="pgc_valor">
                    </div>
                    <div class="finance-form-group">
                        <label>Data do pagamento</label>
                        <input type="date" class="finance-input" id="pgc_data">
                    </div>
                    <div class="finance-form-group">
                        <label>Status</label>
                        <select class="finance-input" id="pgc_status">
                            <option value="pago">Pago</option>
                            <option value="pendente">Pendente</option>
                            <option value="atrasado">Atrasado</option>
                        </select>
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
    const modalConta = new bootstrap.Modal(document.getElementById('modalConta'));
    const modalPagamentoConta = new bootstrap.Modal(document.getElementById('modalPagamentoConta'));
    let contasMensais = [];
    let categoriasDisponiveis = [];
    const pagamentosPorConta = {};

    function mostrarAlerta(msg, tipo = 'success') {
        const alvo = document.getElementById('alertaContas');
        alvo.innerHTML = `<div class="alert alert-${tipo === 'success' ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
            ${msg}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    }

    function carregarCategorias() {
        return window.apiFetch('/categorias').then((categorias) => {
            categoriasDisponiveis = categorias;
            document.getElementById('conta_categoria_id').innerHTML = '<option value="">Nenhuma</option>' +
                categorias.map((c) => `<option value="${c.id}">${c.nome}</option>`).join('');
        });
    }

    function carregarContas() {
        window.apiFetch('/contas-mensais').then((dados) => {
            contasMensais = dados;
            renderizarContas();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    }

    function renderizarContas() {
        const alvo = document.getElementById('listaContas');

        if (!contasMensais.length) {
            alvo.innerHTML = '<p class="text-center text-muted py-4">Nenhuma conta mensal cadastrada ainda.</p>';
            return;
        }

        alvo.innerHTML = contasMensais.map((conta) => `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="deposit-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-uppercase text-muted font-weight-bold">${conta.tipo === 'fixa' ? 'Fixa' : 'Variável'}</small>
                                <div class="deposit-value" style="font-size:1.3rem;">${conta.nome}</div>
                            </div>
                            <span class="${conta.ativa ? 'badge-done' : 'badge-count'}">${conta.ativa ? 'Ativa' : 'Inativa'}</span>
                        </div>

                        <p class="text-muted mb-1">${conta.categoria ? conta.categoria.nome : 'Sem categoria'}</p>
                        <p class="mb-1"><strong>Estimado:</strong> ${conta.valor_estimado ? window.formatarMoeda(conta.valor_estimado) : '-'}</p>
                        <p class="mb-3 text-muted" style="font-size:0.85rem;">Vence todo dia ${conta.dia_vencimento}</p>

                        <div class="d-flex gap-2 mb-3">
                            <button class="btn btn-sm btn-outline-secondary flex-fill" onclick="editarConta(${conta.id})"><i class="ion-edit"></i> Editar</button>
                            <button class="btn btn-sm btn-outline-danger flex-fill" onclick="excluirConta(${conta.id})"><i class="ion-trash-a"></i> Excluir</button>
                        </div>

                        <button class="btn btn-sm btn-outline-primary w-100 mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#pagamentos-${conta.id}" onclick="carregarPagamentos(${conta.id})">
                            <i class="ion-clipboard"></i> Ver Pagamentos
                        </button>

                        <div class="collapse" id="pagamentos-${conta.id}">
                            <div class="d-flex justify-content-end mb-2">
                                <button class="btn btn-sm btn-link" onclick="abrirNovoPagamento(${conta.id})"><i class="ion-plus"></i> Registrar Pagamento</button>
                            </div>
                            <div id="pagamentosLista-${conta.id}" class="small">
                                <p class="text-muted text-center py-2">Carregando...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function carregarPagamentos(contaId) {
        window.apiFetch(`/contas-mensais/${contaId}/pagamentos`).then((pagamentos) => {
            pagamentosPorConta[contaId] = pagamentos;
            renderizarPagamentos(contaId);
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    }

    function renderizarPagamentos(contaId) {
        const alvo = document.getElementById(`pagamentosLista-${contaId}`);
        const pagamentos = pagamentosPorConta[contaId] || [];

        if (!pagamentos.length) {
            alvo.innerHTML = '<p class="text-muted text-center py-2">Nenhum pagamento registrado.</p>';
            return;
        }

        const statusBadge = { pendente: 'badge-count', atrasado: 'badge-count', pago: 'badge-done' };

        alvo.innerHTML = pagamentos.map((pagamento) => `
            <div class="border rounded p-2 mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <strong>${String(pagamento.competencia_mes).padStart(2, '0')}/${pagamento.competencia_ano}</strong>
                    <span class="${statusBadge[pagamento.status]}">${pagamento.status}</span>
                </div>
                <div>${pagamento.valor_pago ? window.formatarMoeda(pagamento.valor_pago) : '-'}</div>
                <div class="d-flex gap-2 mt-2">
                    <button class="btn btn-sm btn-outline-danger" onclick="excluirPagamento(${contaId}, ${pagamento.id})"><i class="ion-trash-a"></i></button>
                </div>
            </div>
        `).join('');
    }

    // --- Conta Mensal ---
    function abrirNovaConta() {
        document.getElementById('formConta').reset();
        document.getElementById('conta_id').value = '';
        document.getElementById('conta_ativa').checked = true;
        document.getElementById('tituloModalConta').textContent = 'Nova Conta Mensal';
    }

    function editarConta(id) {
        const conta = contasMensais.find((c) => c.id === id);
        if (!conta) return;

        document.getElementById('conta_id').value = conta.id;
        document.getElementById('conta_nome').value = conta.nome;
        document.getElementById('conta_categoria_id').value = conta.categoria_id || '';
        document.getElementById('conta_valor_estimado').value = conta.valor_estimado || '';
        document.getElementById('conta_dia_vencimento').value = conta.dia_vencimento;
        document.getElementById('conta_tipo').value = conta.tipo;
        document.getElementById('conta_ativa').checked = !!conta.ativa;
        document.getElementById('conta_observacao').value = conta.observacao || '';
        document.getElementById('tituloModalConta').textContent = 'Editar Conta Mensal';
        modalConta.show();
    }

    function excluirConta(id) {
        if (!confirm('Tem certeza que deseja excluir esta conta mensal?')) return;

        window.apiFetch(`/contas-mensais/${id}`, { method: 'DELETE' }).then(() => {
            mostrarAlerta('Conta excluída com sucesso!');
            carregarContas();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    }

    document.getElementById('formConta').addEventListener('submit', function (event) {
        event.preventDefault();

        const id = document.getElementById('conta_id').value;
        const payload = {
            nome: document.getElementById('conta_nome').value,
            categoria_id: document.getElementById('conta_categoria_id').value || null,
            valor_estimado: document.getElementById('conta_valor_estimado').value || null,
            dia_vencimento: document.getElementById('conta_dia_vencimento').value,
            tipo: document.getElementById('conta_tipo').value,
            ativa: document.getElementById('conta_ativa').checked,
            observacao: document.getElementById('conta_observacao').value || null,
        };

        const url = id ? `/contas-mensais/${id}` : '/contas-mensais';
        const method = id ? 'PUT' : 'POST';

        window.apiFetch(url, { method, body: JSON.stringify(payload) }).then(() => {
            modalConta.hide();
            mostrarAlerta('Conta salva com sucesso!');
            carregarContas();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    });

    // --- Pagamento de Conta ---
    function abrirNovoPagamento(contaId) {
        document.getElementById('formPagamentoConta').reset();
        document.getElementById('pgc_conta_id').value = contaId;
        document.getElementById('pgc_status').value = 'pago';
        const hoje = new Date();
        document.getElementById('pgc_mes').value = hoje.getMonth() + 1;
        document.getElementById('pgc_ano').value = hoje.getFullYear();
        document.getElementById('pgc_data').value = hoje.toISOString().slice(0, 10);
        modalPagamentoConta.show();
    }

    function excluirPagamento(contaId, pagamentoId) {
        if (!confirm('Tem certeza que deseja excluir este pagamento?')) return;

        window.apiFetch(`/contas-mensais/${contaId}/pagamentos/${pagamentoId}`, { method: 'DELETE' }).then(() => {
            mostrarAlerta('Pagamento excluído com sucesso!');
            carregarPagamentos(contaId);
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    }

    document.getElementById('formPagamentoConta').addEventListener('submit', function (event) {
        event.preventDefault();

        const contaId = document.getElementById('pgc_conta_id').value;
        const payload = {
            competencia_mes: document.getElementById('pgc_mes').value,
            competencia_ano: document.getElementById('pgc_ano').value,
            valor_pago: document.getElementById('pgc_valor').value || null,
            data_pagamento: document.getElementById('pgc_data').value || null,
            status: document.getElementById('pgc_status').value,
        };

        window.apiFetch(`/contas-mensais/${contaId}/pagamentos`, { method: 'POST', body: JSON.stringify(payload) }).then(() => {
            modalPagamentoConta.hide();
            mostrarAlerta('Pagamento registrado com sucesso!');
            carregarPagamentos(contaId);
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    });

    carregarCategorias().then(carregarContas);
</script>
@endsection
