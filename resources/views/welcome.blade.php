<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            display: flex;
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            position: fixed;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar h4 {
            text-align: center;
            color: #f8f9fa;
            font-weight: bold;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #495057;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Painel</h4>
        <hr class="bg-light">

        <a href="{{ route('home') }}" class="{{ request()->routeIs('produtos.*') ? 'active' : '' }}">📦 Produtos</a>
        <a href="{{ route('cupons.index') }}" class="{{ request()->routeIs('cupons.*') ? 'active' : '' }}">📜 Cupons</a>
        <a href="{{ route('pedidos.index') }}" class="{{ request()->routeIs('pedidos.*') ? 'active' : '' }}">🛒 Pedidos</a>
    </div>

    <!-- Conteúdo Principal -->
    <div class="content">
        @yield('content')
    </div>

</body>
</html>
