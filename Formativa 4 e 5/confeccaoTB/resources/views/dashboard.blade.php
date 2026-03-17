<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-slate-900">Dashboard</h2>
                    <p class="text-sm text-slate-500">Visão geral do seu negócio</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-slate-600">Olá, <span class="font-semibold text-slate-900">{{ Auth::user()->name }}</span> 👋</p>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Welcome Section -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 p-8 text-white shadow-2xl">
                <div class="absolute inset-0 opacity-20">
                    <svg class="absolute w-96 h-96 -right-20 -top-20" fill="currentColor" viewBox="0 0 200 200">
                        <circle cx="100" cy="100" r="100" fill="none" stroke="currentColor" stroke-width="0.5" opacity="0.1"></circle>
                        <circle cx="100" cy="100" r="75" fill="none" stroke="currentColor" stroke-width="0.5" opacity="0.1"></circle>
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-3xl font-bold mb-2">Bem-vindo de volta! 🎉</h3>
                            <p class="text-blue-100 text-lg">Gerencie seus clientes, pedidos, produtos e estoque com facilidade.</p>
                        </div>
                        <svg class="w-16 h-16 text-blue-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h-2m6-2v2m0 0v2m0-2h2m-8 0h2m-6 2h2m0-2v2m0-2h2m6-4v2m0 0v2m0-2h2m-8 0h2m6-8h2m-6-2v2m0 0v2m0-2h2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Clientes Card -->
                <div class="group relative bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-400/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="p-6 relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">{{ $totalClientes }}</span>
                        </div>
                        <h4 class="text-gray-600 text-sm font-medium mb-1">Total de Clientes</h4>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalClientes }}</p>
                    </div>
                    <div class="h-1 bg-gradient-to-r from-blue-400 to-blue-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                </div>

                <!-- Pedidos Card -->
                <div class="group relative bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-400/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="p-6 relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-purple-100 to-purple-50 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-sm font-semibold">{{ $totalPedidos }}</span>
                        </div>
                        <h4 class="text-gray-600 text-sm font-medium mb-1">Total de Pedidos</h4>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalPedidos }}</p>
                    </div>
                    <div class="h-1 bg-gradient-to-r from-purple-400 to-purple-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                </div>

                <!-- Produtos Card -->
                <div class="group relative bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-400/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="p-6 relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-amber-100 to-amber-50 rounded-lg">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-sm font-semibold">{{ $totalProdutos }}</span>
                        </div>
                        <h4 class="text-gray-600 text-sm font-medium mb-1">Total de Produtos</h4>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalProdutos }}</p>
                    </div>
                    <div class="h-1 bg-gradient-to-r from-amber-400 to-amber-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                </div>

                <!-- Estoque Card -->
                <div class="group relative bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-400/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="p-6 relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-green-100 to-green-50 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">{{ $totalEstoque }}</span>
                        </div>
                        <h4 class="text-gray-600 text-sm font-medium mb-1">Itens em Estoque</h4>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalEstoque }}</p>
                    </div>
                    <div class="h-1 bg-gradient-to-r from-green-400 to-green-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div>
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center space-x-2">
                        <span class="text-3xl">⚡</span>
                        <span>Ações Rápidas</span>
                    </h3>
                    <p class="text-gray-600 text-sm mt-1">Acesso rápido às principais funcionalidades</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Novo Cliente -->
                    <div class="group relative bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-400/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="p-6 relative z-10">
                            <div class="flex items-start justify-between mb-4">
                                <div class="p-3 bg-blue-100 rounded-lg group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <svg class="w-5 h-5 text-blue-300 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">Novo Cliente</h4>
                            <p class="text-gray-600 text-sm mb-4">Adicione um novo cliente ao sistema</p>
                            <a href="{{ route('clientes.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 font-medium text-sm">
                                Criar Cliente
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Novo Pedido -->
                    <div class="group relative bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-400/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="p-6 relative z-10">
                            <div class="flex items-start justify-between mb-4">
                                <div class="p-3 bg-purple-100 rounded-lg group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <svg class="w-5 h-5 text-purple-300 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">Novo Pedido</h4>
                            <p class="text-gray-600 text-sm mb-4">Crie um novo pedido para seus clientes</p>
                            <a href="{{ route('pedidos.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-300 font-medium text-sm">
                                Criar Pedido
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Novo Produto -->
                    <div class="group relative bg-gradient-to-br from-white to-amber-50 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-400/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="p-6 relative z-10">
                            <div class="flex items-start justify-between mb-4">
                                <div class="p-3 bg-amber-100 rounded-lg group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <svg class="w-5 h-5 text-amber-300 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">Novo Produto</h4>
                            <p class="text-gray-600 text-sm mb-4">Adicione um novo produto ao catálogo</p>
                            <a href="{{ route('produtos.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-300 font-medium text-sm">
                                Criar Produto
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('clientes.index') }}" class="group flex items-center space-x-3 p-4 bg-white rounded-lg shadow hover:shadow-lg transition-all duration-300 hover:bg-blue-50">
                    <div class="p-2 bg-blue-100 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Clientes</p>
                        <p class="text-gray-500 text-xs">Ver lista</p>
                    </div>
                </a>

                <a href="{{ route('pedidos.index') }}" class="group flex items-center space-x-3 p-4 bg-white rounded-lg shadow hover:shadow-lg transition-all duration-300 hover:bg-purple-50">
                    <div class="p-2 bg-purple-100 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Pedidos</p>
                        <p class="text-gray-500 text-xs">Ver lista</p>
                    </div>
                </a>

                <a href="{{ route('produtos.index') }}" class="group flex items-center space-x-3 p-4 bg-white rounded-lg shadow hover:shadow-lg transition-all duration-300 hover:bg-amber-50">
                    <div class="p-2 bg-amber-100 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Produtos</p>
                        <p class="text-gray-500 text-xs">Ver lista</p>
                    </div>
                </a>

                <a href="{{ route('estoque.index') }}" class="group flex items-center space-x-3 p-4 bg-white rounded-lg shadow hover:shadow-lg transition-all duration-300 hover:bg-green-50">
                    <div class="p-2 bg-green-100 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Estoque</p>
                        <p class="text-gray-500 text-xs">Ver lista</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>