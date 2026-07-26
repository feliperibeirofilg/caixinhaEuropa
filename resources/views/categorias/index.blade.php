@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="section-title mb-0"><i class="ion-pricetags"></i> Categorias</h3>
        <button type="button" class="btn-pay w-auto px-4" data-bs-toggle="modal" data-bs-target="#modalCategoria" onclick="abrirNovaCategoria()">
            <i class="ion-plus"></i> Nova Categoria
        </button>
    </div>

    <div id="alertaCategorias"></div>

    <div class="table-responsive">
        <table class="table modern-table align-middle">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th class="text-center">Tipo</th>
                    <th class="text-center">Cor</th>
                    <th class="text-center">Ícone</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaCategorias">
                <tr><td colspan="5" class="text-center text-muted py-4">Carregando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Criar/Editar Categoria --}}
<div class="modal fade" id="modalCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px;">
            <form id="formCategoria">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalCategoria">Nova Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="categoria_id">

                    <div class="finance-form-group">
                        <label>Nome</label>
                        <input type="text" class="finance-input" id="categoria_nome" required maxlength="255">
                    </div>

                    <div class="finance-form-group">
                        <label>Tipo</label>
                        <select class="finance-input" id="categoria_tipo" required>
                            <option value="despesa">Despesa</option>
                            <option value="receita">Receita</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Cor</label>
                                <input type="color" class="finance-input p-1" id="categoria_cor" value="#27ae60">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="finance-form-group">
                                <label>Ícone (classe ionicon)</label>
                                <input type="text" class="finance-input" id="categoria_icone" placeholder="ion-cash">
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
    const modalCategoria = new bootstrap.Modal(document.getElementById('modalCategoria'));
    let categorias = [];

    function mostrarAlerta(msg, tipo = 'success') {
        const alvo = document.getElementById('alertaCategorias');
        alvo.innerHTML = `<div class="alert alert-${tipo === 'success' ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
            ${msg}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    }

    function carregarCategorias() {
        window.apiFetch('/categorias').then((dados) => {
            categorias = dados;
            renderizarCategorias();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    }

    function renderizarCategorias() {
        const corpo = document.getElementById('tabelaCategorias');

        if (!categorias.length) {
            corpo.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Nenhuma categoria cadastrada ainda.</td></tr>';
            return;
        }

        corpo.innerHTML = categorias.map((categoria) => `
            <tr>
                <td><strong>${categoria.nome}</strong></td>
                <td class="text-center">
                    <span class="${categoria.tipo === 'receita' ? 'badge-done' : 'badge-count'}">${categoria.tipo === 'receita' ? 'Receita' : 'Despesa'}</span>
                </td>
                <td class="text-center">
                    <span style="display:inline-block;width:22px;height:22px;border-radius:50%;background:${categoria.cor || '#ccc'};border:1px solid #eee;"></span>
                </td>
                <td class="text-center">${categoria.icone ? `<i class="${categoria.icone}"></i>` : '-'}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-secondary" onclick="editarCategoria(${categoria.id})"><i class="ion-edit"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="excluirCategoria(${categoria.id})"><i class="ion-trash-a"></i></button>
                </td>
            </tr>
        `).join('');
    }

    function abrirNovaCategoria() {
        document.getElementById('formCategoria').reset();
        document.getElementById('categoria_id').value = '';
        document.getElementById('categoria_cor').value = '#27ae60';
        document.getElementById('tituloModalCategoria').textContent = 'Nova Categoria';
    }

    function editarCategoria(id) {
        const categoria = categorias.find((c) => c.id === id);
        if (!categoria) return;

        document.getElementById('categoria_id').value = categoria.id;
        document.getElementById('categoria_nome').value = categoria.nome;
        document.getElementById('categoria_tipo').value = categoria.tipo;
        document.getElementById('categoria_cor').value = categoria.cor || '#27ae60';
        document.getElementById('categoria_icone').value = categoria.icone || '';
        document.getElementById('tituloModalCategoria').textContent = 'Editar Categoria';
        modalCategoria.show();
    }

    function excluirCategoria(id) {
        if (!confirm('Tem certeza que deseja excluir esta categoria?')) return;

        window.apiFetch(`/categorias/${id}`, { method: 'DELETE' }).then(() => {
            mostrarAlerta('Categoria excluída com sucesso!');
            carregarCategorias();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    }

    document.getElementById('formCategoria').addEventListener('submit', function (event) {
        event.preventDefault();

        const id = document.getElementById('categoria_id').value;
        const payload = {
            nome: document.getElementById('categoria_nome').value,
            tipo: document.getElementById('categoria_tipo').value,
            cor: document.getElementById('categoria_cor').value,
            icone: document.getElementById('categoria_icone').value || null,
        };

        const url = id ? `/categorias/${id}` : '/categorias';
        const method = id ? 'PUT' : 'POST';

        window.apiFetch(url, { method, body: JSON.stringify(payload) }).then(() => {
            modalCategoria.hide();
            mostrarAlerta('Categoria salva com sucesso!');
            carregarCategorias();
        }).catch((e) => mostrarAlerta(e.message, 'danger'));
    });

    carregarCategorias();
</script>
@endsection
