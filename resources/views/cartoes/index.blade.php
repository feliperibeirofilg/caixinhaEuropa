@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="section-title mb-0"><i class="ion-card"></i> Cartões</h3>
        <button type="button" class="btn-pay w-auto px-4" data-bs-toggle="modal" data-bs-target="#modalCartao" onclick="abrirNovoCartao()">
            <i class="ion-plus"></i> Novo Cartão
        </button>
    </div>

    <div id="alertaCartoes"></div>

    <div class="row" id="listaCartoes">
        <p class="text-center text-muted py-4">Carregando...</p>
    </div>
</div>

{{-- Modal Criar/Editar Cartão --}}
<div class="modal fade" id="modalCartao" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px;">
            <form id="formCartao">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalCartao">Novo Cartão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="cartao_id">

                    <div class="finance-form-group">
                        <label>Nome</label>
                        <input type="text" class="finance-input" id="cartao_nome" required maxlength="255">
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Tipo</label>
                                <select class="finance-input" id="cartao_tipo" required>
                                    <option value="credito">Crédito</option>
                                    <option value="debito">Débito</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Bandeira</label>
                                <input type="text" class="finance-input" id="cartao_bandeira" placeholder="Visa, Mastercard...">
                            </div>
                        </div>
                    </div>

                    <div class="finance-form-group">
                        <label>Limite</label>
                        <input type="number" step="0.01" min="0" class="finance-input" id="cartao_limite">
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Dia de Fechamento</label>
                                <input type="number" min="1" max="31" class="finance-input" id="cartao_dia_fechamento">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Dia de Vencimento</label>
                                <input type="number" min="1" max="31" class="finance-input" id="cartao_dia_vencimento">
                            </div>
                        </div>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="cartao_ativo" checked>
                        <label class="form-check-label" for="cartao_ativo">Cartão ativo</label>
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

{{-- Modal Criar/Editar Fatura --}}
<div class="modal fade" id="modalFatura" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px;">
            <form id="formFatura">
                <div class="modal-header">
                    <h5 class="modal-title">Fatura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="fatura_id">
                    <input type="hidden" id="fatura_cartao_id">

                    <div class="row">
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Mês de referência</label>
                                <input type="number" min="1" max="12" class="finance-input" id="fatura_mes" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Ano de referência</label>
                                <input type="number" min="2000" class="finance-input" id="fatura_ano" required>
                            </div>
                        </div>
                    </div>

                    <div class="finance-form-group">
                        <label>Valor total</label>
                        <input type="number" step="0.01" min="0" class="finance-input" id="fatura_valor_total">
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Fechamento</label>
                                <input type="date" class="finance-input" id="fatura_data_fechamento">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Vencimento</label>
                                <input type="date" class="finance-input" id="fatura_data_vencimento">
                            </div>
                        </div>
                    </div>

                    <div class="finance-form-group">
                        <label>Status</label>
                        <select class="finance-input" id="fatura_status">
                            <option value="aberta">Aberta</option>
                            <option value="fechada">Fechada</option>
                            <option value="paga">Paga</option>
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

{{-- Modal Registrar Pagamento de Fatura --}}
<div class="modal fade" id="modalPagamento" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px;">
            <form id="formPagamento">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Pagamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="pagamento_fatura_id">

                    <div class="finance-form-group">
                        <label>Valor pago</label>
                        <input type="number" step="0.01" min="0" class="finance-input" id="pagamento_valor" required>
                    </div>
                    <div class="finance-form-group">
                        <label>Data do pagamento</label>
                        <input type="date" class="finance-input" id="pagamento_data" required>
                    </div>
                    <div class="finance-form-group">
                        <label>Forma de pagamento</label>
                        <select class="finance-input" id="pagamento_forma">
                            <option value="pix">Pix</option>
                            <option value="debito">Débito</option>
                            <option value="boleto">Boleto</option>
                            <option value="dinheiro">Dinheiro</option>
                        </select>
                    </div>
                    <div class="finance-form-group">
                        <label>Observação</label>
                        <textarea class="finance-input" id="pagamento_observacao" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-pay w-auto px-4">Confirmar Pagamento</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const modalCartao = new bootstrap.Modal(document.getElementById('modalCartao'));
    const modalFatura = new bootstrap.Modal(document.getElementById('modalFatura'));
    const modalPagamento = new bootstrap.Modal(document.getElementById('modalPagamento'));
    let cartoes = [];
    const faturasPorCartao = {};

    function mostrarAlerta(msg, tipo = 'success') {
        const alvo = document.getElementById('alertaCartoes');
        alvo.innerHTML = `<div class="alert alert-${tipo === 'success' ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
            ${msg}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    }

    function carregarCartoes() {
        window.apiFetch('/cartoes').then((dados) => {
            cartoes = dados;
            renderizarCartoes();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    }

    function renderizarCartoes() {
        const alvo = document.getElementById('listaCartoes');

        if (!cartoes.length) {
            alvo.innerHTML = '<p class="text-center text-muted py-4">Nenhum cartão cadastrado ainda.</p>';
            return;
        }

        alvo.innerHTML = cartoes.map((cartao) => `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="deposit-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-uppercase text-muted font-weight-bold">${cartao.tipo === 'credito' ? 'Crédito' : 'Débito'}</small>
                                <div class="deposit-value" style="font-size:1.4rem;">${cartao.nome}</div>
                            </div>
                            <span class="${cartao.ativo ? 'badge-done' : 'badge-count'}">${cartao.ativo ? 'Ativo' : 'Inativo'}</span>
                        </div>

                        <p class="text-muted mb-2">${cartao.bandeira || '-'}</p>
                        <p class="mb-1"><strong>Limite:</strong> ${cartao.limite ? window.formatarMoeda(cartao.limite) : '-'}</p>
                        <p class="mb-3 text-muted" style="font-size:0.85rem;">Fecha dia ${cartao.dia_fechamento ?? '-'} · Vence dia ${cartao.dia_vencimento ?? '-'}</p>

                        <div class="d-flex gap-2 mb-3">
                            <button class="btn btn-sm btn-outline-secondary flex-fill" onclick="editarCartao(${cartao.id})"><i class="ion-edit"></i> Editar</button>
                            <button class="btn btn-sm btn-outline-danger flex-fill" onclick="excluirCartao(${cartao.id})"><i class="ion-trash-a"></i> Excluir</button>
                        </div>

                        <button class="btn btn-sm btn-outline-primary w-100 mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#faturas-${cartao.id}" onclick="carregarFaturas(${cartao.id})">
                            <i class="ion-clipboard"></i> Ver Faturas
                        </button>

                        <div class="collapse" id="faturas-${cartao.id}">
                            <div class="d-flex justify-content-end mb-2">
                                <button class="btn btn-sm btn-link" onclick="abrirNovaFatura(${cartao.id})"><i class="ion-plus"></i> Nova Fatura</button>
                            </div>
                            <div id="faturasLista-${cartao.id}" class="small">
                                <p class="text-muted text-center py-2">Carregando...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function carregarFaturas(cartaoId) {
        window.apiFetch(`/cartoes/${cartaoId}/faturas`).then((faturas) => {
            faturasPorCartao[cartaoId] = faturas;
            renderizarFaturas(cartaoId);
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    }

    function renderizarFaturas(cartaoId) {
        const alvo = document.getElementById(`faturasLista-${cartaoId}`);
        const faturas = faturasPorCartao[cartaoId] || [];

        if (!faturas.length) {
            alvo.innerHTML = '<p class="text-muted text-center py-2">Nenhuma fatura registrada.</p>';
            return;
        }

        const statusBadge = { aberta: 'badge-count', fechada: 'badge-count', paga: 'badge-done' };

        alvo.innerHTML = faturas.map((fatura) => `
            <div class="border rounded p-2 mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <strong>${String(fatura.mes_referencia).padStart(2, '0')}/${fatura.ano_referencia}</strong>
                    <span class="${statusBadge[fatura.status]}">${fatura.status}</span>
                </div>
                <div>${window.formatarMoeda(fatura.valor_total)}</div>
                <div class="d-flex gap-2 mt-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick='editarFatura(${cartaoId}, ${fatura.id})'><i class="ion-edit"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="excluirFatura(${cartaoId}, ${fatura.id})"><i class="ion-trash-a"></i></button>
                    ${fatura.status !== 'paga' ? `<button class="btn btn-sm btn-pay" style="width:auto;padding:6px 12px;" onclick="abrirPagamento(${fatura.id}, ${fatura.valor_total})">Pagar</button>` : ''}
                </div>
            </div>
        `).join('');
    }

    // --- Cartão ---
    function abrirNovoCartao() {
        document.getElementById('formCartao').reset();
        document.getElementById('cartao_id').value = '';
        document.getElementById('cartao_ativo').checked = true;
        document.getElementById('tituloModalCartao').textContent = 'Novo Cartão';
    }

    function editarCartao(id) {
        const cartao = cartoes.find((c) => c.id === id);
        if (!cartao) return;

        document.getElementById('cartao_id').value = cartao.id;
        document.getElementById('cartao_nome').value = cartao.nome;
        document.getElementById('cartao_tipo').value = cartao.tipo;
        document.getElementById('cartao_bandeira').value = cartao.bandeira || '';
        document.getElementById('cartao_limite').value = cartao.limite || '';
        document.getElementById('cartao_dia_fechamento').value = cartao.dia_fechamento || '';
        document.getElementById('cartao_dia_vencimento').value = cartao.dia_vencimento || '';
        document.getElementById('cartao_ativo').checked = !!cartao.ativo;
        document.getElementById('tituloModalCartao').textContent = 'Editar Cartão';
        modalCartao.show();
    }

    function excluirCartao(id) {
        if (!confirm('Tem certeza que deseja excluir este cartão? As faturas associadas também serão removidas.')) return;

        window.apiFetch(`/cartoes/${id}`, { method: 'DELETE' }).then(() => {
            mostrarAlerta('Cartão excluído com sucesso!');
            carregarCartoes();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    }

    document.getElementById('formCartao').addEventListener('submit', function (event) {
        event.preventDefault();

        const id = document.getElementById('cartao_id').value;
        const payload = {
            nome: document.getElementById('cartao_nome').value,
            tipo: document.getElementById('cartao_tipo').value,
            bandeira: document.getElementById('cartao_bandeira').value || null,
            limite: document.getElementById('cartao_limite').value || null,
            dia_fechamento: document.getElementById('cartao_dia_fechamento').value || null,
            dia_vencimento: document.getElementById('cartao_dia_vencimento').value || null,
            ativo: document.getElementById('cartao_ativo').checked,
        };

        const url = id ? `/cartoes/${id}` : '/cartoes';
        const method = id ? 'PUT' : 'POST';

        window.apiFetch(url, { method, body: JSON.stringify(payload) }).then(() => {
            modalCartao.hide();
            mostrarAlerta('Cartão salvo com sucesso!');
            carregarCartoes();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    });

    // --- Fatura ---
    function abrirNovaFatura(cartaoId) {
        document.getElementById('formFatura').reset();
        document.getElementById('fatura_id').value = '';
        document.getElementById('fatura_cartao_id').value = cartaoId;
        document.getElementById('fatura_status').value = 'aberta';
        const hoje = new Date();
        document.getElementById('fatura_mes').value = hoje.getMonth() + 1;
        document.getElementById('fatura_ano').value = hoje.getFullYear();
        modalFatura.show();
    }

    function editarFatura(cartaoId, faturaId) {
        const fatura = (faturasPorCartao[cartaoId] || []).find((f) => f.id === faturaId);
        if (!fatura) return;

        document.getElementById('fatura_id').value = fatura.id;
        document.getElementById('fatura_cartao_id').value = cartaoId;
        document.getElementById('fatura_mes').value = fatura.mes_referencia;
        document.getElementById('fatura_ano').value = fatura.ano_referencia;
        document.getElementById('fatura_valor_total').value = fatura.valor_total || '';
        document.getElementById('fatura_data_fechamento').value = fatura.data_fechamento || '';
        document.getElementById('fatura_data_vencimento').value = fatura.data_vencimento || '';
        document.getElementById('fatura_status').value = fatura.status;
        modalFatura.show();
    }

    function excluirFatura(cartaoId, faturaId) {
        if (!confirm('Tem certeza que deseja excluir esta fatura?')) return;

        window.apiFetch(`/cartoes/${cartaoId}/faturas/${faturaId}`, { method: 'DELETE' }).then(() => {
            mostrarAlerta('Fatura excluída com sucesso!');
            carregarFaturas(cartaoId);
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    }

    document.getElementById('formFatura').addEventListener('submit', function (event) {
        event.preventDefault();

        const cartaoId = document.getElementById('fatura_cartao_id').value;
        const id = document.getElementById('fatura_id').value;
        const payload = {
            mes_referencia: document.getElementById('fatura_mes').value,
            ano_referencia: document.getElementById('fatura_ano').value,
            valor_total: document.getElementById('fatura_valor_total').value || 0,
            data_fechamento: document.getElementById('fatura_data_fechamento').value || null,
            data_vencimento: document.getElementById('fatura_data_vencimento').value || null,
            status: document.getElementById('fatura_status').value,
        };

        const url = id ? `/cartoes/${cartaoId}/faturas/${id}` : `/cartoes/${cartaoId}/faturas`;
        const method = id ? 'PUT' : 'POST';

        window.apiFetch(url, { method, body: JSON.stringify(payload) }).then(() => {
            modalFatura.hide();
            mostrarAlerta('Fatura salva com sucesso!');
            carregarFaturas(cartaoId);
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    });

    // --- Pagamento de Fatura ---
    function abrirPagamento(faturaId, valorSugerido) {
        document.getElementById('formPagamento').reset();
        document.getElementById('pagamento_fatura_id').value = faturaId;
        document.getElementById('pagamento_valor').value = valorSugerido || '';
        document.getElementById('pagamento_data').value = new Date().toISOString().slice(0, 10);
        modalPagamento.show();
    }

    document.getElementById('formPagamento').addEventListener('submit', function (event) {
        event.preventDefault();

        const payload = {
            fatura_cartao_id: document.getElementById('pagamento_fatura_id').value,
            valor: document.getElementById('pagamento_valor').value,
            data_pagamento: document.getElementById('pagamento_data').value,
            forma_pagamento: document.getElementById('pagamento_forma').value,
            observacao: document.getElementById('pagamento_observacao').value || null,
        };

        window.apiFetch('/pagamentos', { method: 'POST', body: JSON.stringify(payload) }).then(() => {
            modalPagamento.hide();
            mostrarAlerta('Pagamento registrado com sucesso!');
            carregarCartoes();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    });

    carregarCartoes();
</script>
@endsection
