<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confecção TB2 - Gestão Inteligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient {
            background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
        }
        .btn-primary {
            background-color: #0f62f4;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0d52cc;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 98, 244, 0.25);
        }
        .btn-outline {
            border: 2px solid #0f62f4;
            color: #0f62f4;
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            background-color: #f0f7ff;
            transform: translateY(-1px);
        }
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-white text-slate-900">

    <!-- Header / Navbar -->
    <header class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo-tb2.png') }}" alt="Logo" class="h-10 w-auto">
            <span class="text-xl font-extrabold tracking-tight text-slate-800">Confecção TB2</span>
        </div>
        
        <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
            <a href="#" class="text-blue-600 border-b-2 border-blue-600 pb-1">Início</a>
            <a href="#" class="hover:text-blue-600 transition-colors">Recursos</a>
            <a href="#" class="hover:text-blue-600 transition-colors">Sobre</a>
            <a href="{{ route('filament.admin.auth.login') }}" class="btn-primary text-white px-6 py-2.5 rounded-lg flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Entrar
            </a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-gradient overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 py-16 md:py-24 flex flex-col md:flex-row items-center gap-12">
            <div class="flex-1 text-center md:text-left space-y-8">
                <h1 class="text-5xl md:text-6xl font-extrabold text-slate-900 leading-[1.1]">
                    Gestão <span class="text-blue-600">inteligente</span> para sua confecção.
                </h1>
                <p class="text-lg text-slate-600 max-w-lg leading-relaxed">
                    A plataforma completa para controlar clientes, fornecedores, produtos, insumos, estoque e pedidos em um só lugar.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <a href="{{ route('filament.admin.auth.login') }}" class="btn-primary text-white px-8 py-4 rounded-xl font-bold flex items-center gap-3 w-full sm:w-auto justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Acessar Login
                    </a>
                    <a href="#" class="btn-outline px-8 py-4 rounded-xl font-bold flex items-center gap-3 w-full sm:w-auto justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Conhecer o Sistema
                    </a>
                </div>

                <div class="flex flex-wrap justify-center md:justify-start gap-6 pt-4">
                    <div class="flex items-center gap-2 text-sm font-medium text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Fácil de usar
                    </div>
                    <div class="flex items-center gap-2 text-sm font-medium text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Seguro
                    </div>
                    <div class="flex items-center gap-2 text-sm font-medium text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Acesso rápido
                    </div>
                </div>
            </div>

            <div class="flex-1 relative">
                <div class="relative z-10 rounded-2xl overflow-hidden shadow-2xl border border-slate-200">
                    <img src="{{ asset('images/hero-home.png') }}" alt="Dashboard Preview" class="w-full h-auto">
                </div>
                <!-- Abstract decorative element -->
                <div class="absolute -top-10 -right-10 w-64 h-64 bg-blue-100 rounded-full blur-3xl opacity-60 z-0"></div>
            </div>
        </div>
    </section>

    <!-- Quick Features -->
    <section class="max-w-7xl mx-auto px-6 -mt-12 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-xl card-shadow border border-slate-50 flex items-start gap-4">
                <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Controle de Pedidos</h3>
                    <p class="text-xs text-slate-500 mt-1">Acompanhe todo o fluxo de pedidos de forma simples e organizada.</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl card-shadow border border-slate-50 flex items-start gap-4">
                <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Gestão de Estoque</h3>
                    <p class="text-xs text-slate-500 mt-1">Tenha controle total do estoque de produtos e insumos em tempo real.</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl card-shadow border border-slate-50 flex items-start gap-4">
                <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Cadastro de Clientes</h3>
                    <p class="text-xs text-slate-500 mt-1">Gerencie seus clientes e histórico de pedidos com praticidade.</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl card-shadow border border-slate-50 flex items-start gap-4">
                <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Fornecedores e Insumos</h3>
                    <p class="text-xs text-slate-500 mt-1">Cadastre fornecedores e insumos essenciais para sua produção.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center space-y-2">
                <div class="flex justify-center text-blue-600 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="text-4xl font-extrabold text-slate-900">124</div>
                <div class="text-sm font-semibold text-slate-500">Clientes cadastrados</div>
            </div>
            <div class="text-center space-y-2">
                <div class="flex justify-center text-blue-600 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div class="text-4xl font-extrabold text-slate-900">38</div>
                <div class="text-sm font-semibold text-slate-500">Pedidos registrados</div>
            </div>
            <div class="text-center space-y-2">
                <div class="flex justify-center text-blue-600 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="text-4xl font-extrabold text-slate-900">86</div>
                <div class="text-sm font-semibold text-slate-500">Produtos cadastrados</div>
            </div>
            <div class="text-center space-y-2">
                <div class="flex justify-center text-blue-600 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <div class="text-4xl font-extrabold text-slate-900">24</div>
                <div class="text-sm font-semibold text-slate-500">Insumos cadastrados</div>
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section class="bg-slate-50 py-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div class="space-y-8">
                    <h2 class="text-3xl font-extrabold text-slate-900">Por que escolher a Confecção TB2?</h2>
                    <p class="text-slate-600 leading-relaxed">
                        Desenvolvido especialmente para confecções que buscam mais controle, organização e produtividade. Nossa plataforma é intuitiva, segura e feita para simplificar sua rotina, permitindo que você foque no que realmente importa: criar e crescer.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <div class="text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm">Segurança</h4>
                            <p class="text-xs text-slate-500">Seus dados protegidos com tecnologia de ponta.</p>
                        </div>
                        <div class="space-y-3">
                            <div class="text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm">Agilidade</h4>
                            <p class="text-xs text-slate-500">Informações sempre atualizadas em tempo real.</p>
                        </div>
                        <div class="space-y-3">
                            <div class="text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm">Relatórios</h4>
                            <p class="text-xs text-slate-500">Relatórios completos para melhores decisões.</p>
                        </div>
                        <div class="space-y-3">
                            <div class="text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm">Acesso em Nuvem</h4>
                            <p class="text-xs text-slate-500">Acesse de qualquer lugar, a qualquer momento.</p>
                        </div>
                    </div>
                </div>
                
                <div class="rounded-2xl overflow-hidden shadow-xl">
                    <img src="https://images.unsplash.com/photo-1556905055-8f358a7a4bb4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Confeccao Work" class="w-full h-auto">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8 mb-8">
                <div class="text-sm text-slate-500">
                    &copy; 2026 Confecção TB2. Todos os direitos reservados.
                </div>
                
                <nav class="flex gap-8 text-sm font-semibold text-slate-500">
                    <a href="#" class="hover:text-blue-600 transition-colors">Início</a>
                    <a href="#" class="hover:text-blue-600 transition-colors">Recursos</a>
                    <a href="#" class="hover:text-blue-600 transition-colors">Sobre</a>
                    <a href="#" class="hover:text-blue-600 transition-colors">Contato</a>
                </nav>
                
                <div class="text-sm text-slate-500 font-medium">
                    Confecção <span class="text-blue-600 font-bold">TB2</span> — Sistema de Gestão para Confecções
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
