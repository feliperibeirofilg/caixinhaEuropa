<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Europa Ai Vou eu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    
   <body>
    
    <div id="app">
        
        {{-- INICIO DA NAVBAR --}}
        <nav class="custom-navbar">
            {{-- Lado Esquerdo: Logo --}}
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('icon/icon.png') }}" alt="Logo" height="35">
                <span>Europa Aí Vou Eu</span>
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
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    
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
</body>
</html>