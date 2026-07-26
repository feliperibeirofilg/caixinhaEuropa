@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="page-title">Crie a sua meta personalizada</h1>
            <p class="page-subtitle">Escolha o nome, o valor e monte a distribuição dos depósitos.</p>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="post" action="{{ route('caixinha.personalizada.criar') }}" id="formMetaPersonalizada">
                @csrf

                <div class="finance-form-group">
                    <label>Nome da meta</label>
                    <input type="text" class="finance-input" name="nome" value="{{ old('nome') }}" placeholder="Ex: Viagem para Europa 2027" required maxlength="255">
                </div>

                <div class="finance-form-group">
                    <label>Valor da meta</label>
                    <input type="number" step="0.01" min="0.01" class="finance-input" id="meta_valor" name="meta_valor" value="{{ old('meta_valor') }}" required>
                </div>

                <label class="d-block mb-2" style="color:#334155;font-weight:600;font-size:0.85rem;">Distribuição dos depósitos</label>

                <div id="linhasDistribuicao">
                    @php $linhasAntigas = old('distribuicao', [['valor' => '', 'quantidade' => ''], ['valor' => '', 'quantidade' => '']]); @endphp
                    @foreach ($linhasAntigas as $linha)
                        <div class="row g-2 mb-2 linha-distribuicao">
                            <div class="col-5">
                                <input type="number" step="0.01" min="0.01" class="finance-input campo-valor" name="distribuicao[{{ $loop->index }}][valor]" value="{{ $linha['valor'] }}" placeholder="Valor (ex: 50)" required>
                            </div>
                            <div class="col-5">
                                <input type="number" min="1" class="finance-input campo-quantidade" name="distribuicao[{{ $loop->index }}][quantidade]" value="{{ $linha['quantidade'] }}" placeholder="Quantidade" required>
                            </div>
                            <div class="col-2 d-flex align-items-center">
                                <button type="button" class="btn btn-outline-danger btn-sm remover-linha"><i class="ion-trash-a"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-link px-0 mb-3" id="adicionarLinha"><i class="ion-plus"></i> Adicionar outro valor</button>

                <div class="d-flex justify-content-between align-items-center mb-4 p-3" style="background:#f8fafc;border-radius:12px;">
                    <span>Total distribuído: <strong id="totalDistribuido">R$ 0,00</strong></span>
                    <span id="statusBate" class="fw-bold"></span>
                </div>

                <button type="submit" class="btn-choose" style="background-color:#8e44ad;">Criar Meta</button>

                <div class="text-center mt-3">
                    <a href="{{ route('caixinha.escolha.form') }}" style="color:#7f8c8d;text-decoration:none;">Voltar para as metas prontas</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let proximoIndice = document.querySelectorAll('.linha-distribuicao').length;

    function formatarMoedaLocal(valor) {
        return 'R$ ' + Number(valor || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function recalcularTotal() {
        let total = 0;
        document.querySelectorAll('.linha-distribuicao').forEach((linha) => {
            const valor = parseFloat(linha.querySelector('.campo-valor').value) || 0;
            const quantidade = parseInt(linha.querySelector('.campo-quantidade').value) || 0;
            total += valor * quantidade;
        });

        document.getElementById('totalDistribuido').textContent = formatarMoedaLocal(total);

        const meta = parseFloat(document.getElementById('meta_valor').value) || 0;
        const status = document.getElementById('statusBate');

        if (meta <= 0) {
            status.textContent = '';
            return;
        }

        if (Math.abs(total - meta) < 0.01) {
            status.textContent = '✓ Bate com a meta';
            status.style.color = '#27ae60';
        } else {
            status.textContent = total > meta ? 'Passou da meta' : 'Ainda falta';
            status.style.color = '#e74c3c';
        }
    }

    function criarLinha() {
        const div = document.createElement('div');
        div.className = 'row g-2 mb-2 linha-distribuicao';
        div.innerHTML = `
            <div class="col-5">
                <input type="number" step="0.01" min="0.01" class="finance-input campo-valor" name="distribuicao[${proximoIndice}][valor]" placeholder="Valor (ex: 50)" required>
            </div>
            <div class="col-5">
                <input type="number" min="1" class="finance-input campo-quantidade" name="distribuicao[${proximoIndice}][quantidade]" placeholder="Quantidade" required>
            </div>
            <div class="col-2 d-flex align-items-center">
                <button type="button" class="btn btn-outline-danger btn-sm remover-linha"><i class="ion-trash-a"></i></button>
            </div>
        `;
        proximoIndice++;
        document.getElementById('linhasDistribuicao').appendChild(div);
    }

    document.getElementById('adicionarLinha').addEventListener('click', criarLinha);

    document.getElementById('linhasDistribuicao').addEventListener('click', (event) => {
        const botao = event.target.closest('.remover-linha');
        if (!botao) return;

        const linhas = document.querySelectorAll('.linha-distribuicao');
        if (linhas.length <= 1) return;

        botao.closest('.linha-distribuicao').remove();
        recalcularTotal();
    });

    document.getElementById('linhasDistribuicao').addEventListener('input', recalcularTotal);
    document.getElementById('meta_valor').addEventListener('input', recalcularTotal);

    recalcularTotal();
</script>
@endsection
