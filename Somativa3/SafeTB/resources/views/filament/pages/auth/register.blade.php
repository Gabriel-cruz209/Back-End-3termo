<div class="flex min-h-screen">
    <!-- Lado Esquerdo: Imagem e Boas-vindas -->
    <div class="hidden lg:flex flex-col justify-between w-1/2 bg-slate-900 text-white p-12 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1544717297-fa15c399642f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80');">
        <div class="absolute inset-0 bg-slate-900/80"></div>
        
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-12">
                <x-heroicon-o-shield-check class="w-8 h-8 text-blue-400" />
                <span class="text-2xl font-bold tracking-tight">SAFE</span>
            </div>
            
            <h1 class="text-4xl font-bold mb-6 leading-tight">Gestão escolar simples e segura</h1>
            <p class="text-lg text-slate-300 max-w-md">
                Centralize autorizações, comunicações e informações da sua escola em um sistema moderno e confiável.
            </p>
        </div>

        <div class="relative z-10">
            <div class="flex -space-x-2 overflow-hidden mb-4">
                <img class="inline-block h-8 w-8 rounded-full ring-2 ring-slate-900" src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                <img class="inline-block h-8 w-8 rounded-full ring-2 ring-slate-900" src="https://images.unsplash.com/photo-1550525811-e5869dd03032?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                <img class="inline-block h-8 w-8 rounded-full ring-2 ring-slate-900" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
            </div>
            <p class="text-sm text-slate-400">Junte-se a centenas de escolas que já utilizam o SAFE.</p>
        </div>
    </div>

    <!-- Lado Direito: Formulário de Cadastro -->
    <div class="flex flex-col justify-center items-center w-full lg:w-1/2 bg-slate-50 p-8">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl p-8 border border-slate-200">
            <div class="flex items-center gap-4 mb-8">
                <div class="p-3 bg-blue-50 rounded-xl">
                    <x-heroicon-o-user-plus class="w-8 h-8 text-blue-600" />
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Criar Conta</h2>
                    <p class="text-slate-500">Preencha os dados abaixo para criar sua conta no SAFE.</p>
                </div>
            </div>

            <form wire:submit="register">
                {{ $this->form }}

                <div class="mt-8">
                    <x-filament::button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 py-3 rounded-xl transition-all shadow-lg shadow-blue-200">
                        <span class="flex items-center justify-center gap-2 font-bold">
                            <x-heroicon-m-user-plus class="w-5 h-5" />
                            Cadastrar
                        </span>
                    </x-filament::button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-500">
                    Já possui uma conta? 
                    <a href="{{ filament()->getLoginUrl() }}" class="font-bold text-blue-600 hover:text-blue-500 transition-colors">
                        Fazer login
                    </a>
                </p>
            </div>
        </div>

        <div class="mt-8 text-center">
            <p class="text-xs text-slate-400">
                &copy; {{ date('Y') }} SAFE - Sistema de Autorização e Fluxo Escolar. Todos os direitos reservados.
            </p>
        </div>
    </div>
</div>
