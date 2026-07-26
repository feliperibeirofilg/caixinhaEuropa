@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="section-title mb-0"><i class="ion-arrow-swap"></i> Transferências para a Caixinha</h3>
        <button type="button" class="btn-pay w-auto px-4" data-bs-toggle="modal" data-bs-target="#modalTransferencia" onclick="abrirNovaTransferencia()">
            <i class="ion-plus"></i> Nova Transferência
        </button>
    </div>

    <div id="alertaTransferencias"></div>

    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div class="balance-card text-center">
                <div class="card-label">Total Transferido</div>
                <h2 id="totalTransferido">R$ 0,00</h2>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table align-middle">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th class="text-center">Data</th>
                    <th class="text-end">Valor</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaTransferencias">
                <tr><td colspan="4" class="text-center text-muted py-4">Carregando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Criar/Editar Transferência --}}
<div class="modal fade" id="modalTransferencia" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px;">
            <form id="formTransferencia">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalTransferencia">Nova Transferência</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="transferencia_id">

                    <div class="finance-form-group">
                        <label>Descrição</label>
                        <input type="text" class="finance-input" id="transferencia_descricao" maxlength="255" placeholder="Ex: Aporte mensal">
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Valor</label>
                                <input type="number" step="0.01" min="0" class="finance-input" id="transferencia_valor" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Data</label>
                                <input type="date" class="finance-input" id="transferencia_data" required>
                            </div>
                        </div>
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
    const modalTransferencia = new bootstrap.Modal(document.getElementById('modalTransferencia'));
    let transferencias = [];

    function mostrarAlerta(msg, tipo = 'success') {
        const alvo = document.getElementById('alertaTransferencias');
        alvo.innerHTML = `<div class="alert alert-${tipo === 'success' ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
            ${msg}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    }

    function carregarTransferencias() {
        window.apiFetch('/transferencias').then((dados) => {
            transferencias = dados;
            renderizarTransferencias();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    }

    function renderizarTransferencias() {
        const corpo = document.getElementById('tabelaTransferencias');

        const total = transferencias.reduce((soma, t) => soma + Number(t.valor), 0);
        document.getElementById('totalTransferido').textContent = window.formatarMoeda(total);

        if (!transferencias.length) {
            corpo.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">Nenhuma transferência registrada ainda.</td></tr>';
            return;
        }

        corpo.innerHTML = transferencias.map((transferencia) => `
            <tr>
                <td>${transferencia.descricao || '-'}</td>
                <td class="text-center">${new Date(transferencia.data + 'T00:00:00').toLocaleDateString('pt-BR')}</td>
                <td class="text-end" style="color:#27ae60;font-weight:600;">${window.formatarMoeda(transferencia.valor)}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-secondary" onclick="editarTransferencia(${transferencia.id})"><i class="ion-edit"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="excluirTransferencia(${transferencia.id})"><i class="ion-trash-a"></i></button>
                </td>
            </tr>
        `).join('');
    }

    function abrirNovaTransferencia() {
        document.getElementById('formTransferencia').reset();
        document.getElementById('transferencia_id').value = '';
        document.getElementById('transferencia_data').value = new Date().toISOString().slice(0, 10);
        document.getElementById('tituloModalTransferencia').textContent = 'Nova Transferência';
    }

    function editarTransferencia(id) {
        const transferencia = transferencias.find((t) => t.id === id);
        if (!transferencia) return;

        document.getElementById('transferencia_id').value = transferencia.id;
        document.getElementById('transferencia_descricao').value = transferencia.descricao || '';
        document.getElementById('transferencia_valor').value = transferencia.valor;
        document.getElementById('transferencia_data').value = transferencia.data;
        document.getElementById('tituloModalTransferencia').textContent = 'Editar Transferência';
        modalTransferencia.show();
    }

    function excluirTransferencia(id) {
        if (!confirm('Tem certeza que deseja excluir esta transferência?')) return;

        window.apiFetch(`/transferencias/${id}`, { method: 'DELETE' }).then(() => {
            mostrarAlerta('Transferência excluída com sucesso!');
            carregarTransferencias();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    }

    document.getElementById('formTransferencia').addEventListener('submit', function (event) {
        event.preventDefault();

        const id = document.getElementById('transferencia_id').value;
        const payload = {
            descricao: document.getElementById('transferencia_descricao').value || null,
            valor: document.getElementById('transferencia_valor').value,
            data: document.getElementById('transferencia_data').value,
        };

        const url = id ? `/transferencias/${id}` : '/transferencias';
        const method = id ? 'PUT' : 'POST';

        window.apiFetch(url, { method, body: JSON.stringify(payload) }).then(() => {
            modalTransferencia.hide();
            mostrarAlerta('Transferência salva com sucesso!');
            carregarTransferencias();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    });

    carregarTransferencias();
</script>
@endsection
