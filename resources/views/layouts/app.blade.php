<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Close Finance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
    
   <body>
    
    <div id="app">
        
        {{-- INICIO DA NAVBAR --}}
        <nav class="custom-navbar">
            {{-- Lado Esquerdo: Logo --}}
            <a class="navbar-brand" href="{{ auth()->check() ? route('inicio') : url('/') }}">
                <img src="{{ asset('icon/close-finance-2.png') }}" alt="Logo" height="60">
                <span>Close Finance</span>
            </a>

            {{-- Lado Direito: Menu --}}
            <ul class="navbar-links">

                @guest
                    @if (Route::has('login'))
                        <li><a href="{{ route('login') }}" class="nav-btn btn-login">Entrar</a></li>
                    @endif

                    @if (Route::has('register'))
                        <li><a href="{{ route('register') }}" class="nav-btn btn-register">Cadastrar</a></li>
                    @endif

                @else
                    <li><a href="{{ route('inicio') }}">Início</a></li>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('painel.financas') }}">Finanças</a></li>
                    <li><a href="{{ route('painel.cartoes') }}">Cartões</a></li>
                    <li><a href="{{ route('painel.contas-mensais') }}">Contas Mensais</a></li>
                    <li><a href="{{ route('painel.categorias') }}">Categorias</a></li>
                    <li><a href="{{ route('painel.transferencias') }}">Transferências</a></li>

                    <li>
                        <span style="color: #ccc;">|</span>
                    </li>

                    <li>
                        <span class="user-greeting">Olá, {{ Auth::user()->nome }}</span> </li>

                    <li>
                        <a href="{{ route('logout') }}"
                           class="nav-btn btn-logout"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ion-log-out"></i> Sair
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </nav>
        {{-- FIM DA NAVBAR --}}

        <div class="main-content">
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Helper simples para chamadas às rotas JSON do painel financeiro.
        window.apiFetch = function (url, options = {}) {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const headers = Object.assign({
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token,
            }, options.headers || {});

            return fetch(url, Object.assign({}, options, { headers })).then(async (res) => {
                if (res.status === 204) return null;
                const data = await res.json().catch(() => null);
                if (!res.ok) {
                    const erro = new Error((data && (data.message || Object.values(data.errors || {})[0]?.[0])) || 'Ocorreu um erro.');
                    erro.data = data;
                    throw erro;
                }
                return data;
            });
        };

        window.formatarMoeda = function (valor) {
            return 'R$ ' + Number(valor || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        };
    </script>
    @yield('scripts')
</body>
</html>