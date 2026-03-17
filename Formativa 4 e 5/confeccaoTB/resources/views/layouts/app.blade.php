<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script>
            $(document).ready(function(){
                // Máscara para CPF
                $('input[name="cpf"]').mask('000.000.000-00');
                // Máscara para Telefone
                $('input[name="telefone"]').mask('(00) 00000-0000');
                // Máscara para CEP (se houver)
                $('input[name="cep"]').mask('00000-000');
                // Máscara para CNPJ (se houver)
                $('input[name="cnpj"]').mask('00.000.000/0000-00');
                // Máscara para Data (se houver)
                $('input[name="data_validade"], input[name="data_nascimento"]').mask('00/00/0000');
                // Máscara para Preço
                $('input[name="preco_venda"], input[name="preco_custo"], input[name="valor_total"]').mask('000.000.000.000.000,00', {reverse: true});
            });
        </script>
    </body>
</html>
