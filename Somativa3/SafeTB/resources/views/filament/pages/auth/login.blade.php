<div class="flex min-h-screen">
    <!-- Lado Esquerdo: Imagem e Boas-vindas -->
    <div class="hidden lg:flex flex-col justify-between w-1/2 bg-blue-900 text-white p-12 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1523050853063-9136a677de47?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80');">
        <div class="absolute inset-0 bg-blue-900/80"></div>
        
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-12">
                <x-heroicon-o-shield-check class="w-8 h-8 text-blue-400" />
                <span class="text-2xl font-bold tracking-tight">SAFE</span>
            </div>
            
            <h1 class="text-4xl font-bold mb-6 leading-tight">Bem-vindo de volta!</h1>
            <p class="text-lg text-blue-100 max-w-md">
                Acesse o SAFE e gerencie autorizações com segurança e eficiência. Mais controle no fluxo escolar, mais tranquilidade para todos.
            </p>
        </div>

        <div class="relative z-10 text-sm text-blue-300 flex items-center gap-2">
            <x-heroicon-o-lock-closed class="w-4 h-4" />
            Plataforma segura e confiável para escolas e famílias.
        </div>
    </div>

    <!-- Lado Direito: Formulário de Login -->
    <div class="flex flex-col justify-center items-center w-full lg:w-1/2 bg-slate-50 p-8">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border border-slate-200">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900">Acesse sua conta</h2>
                <p class="text-slate-500 mt-2">Entre com seu e-mail e senha para continuar</p>
            </div>

            <form wire:submit="authenticate" class="space-y-6">
                {{ $this->form }}

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                        <input type="checkbox" wire:model="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        Lembrar de mim
                    </label>
                    
                    <a href="{{ filament()->getResetPasswordUrl() }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors">
                        Esqueci minha senha
                    </a>
                </div>

                <x-filament::button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 py-3 rounded-xl transition-all shadow-lg shadow-blue-200">
                    <span class="flex items-center justify-center gap-2">
                        <x-heroicon-m-arrow-right-on-rectangle class="w-5 h-5" />
                        Entrar
                    </span>
                </x-filament::button>
            </form>

            <div class="mt-8 pt-8 border-t border-slate-100">
                <p class="text-center text-sm text-slate-500">
                    Não possui uma conta? 
                    <a href="{{ filament()->getRegistrationUrl() }}" class="font-bold text-blue-600 hover:text-blue-500 transition-colors">
                        Cadastre-se
                    </a>
                </p>
            </div>
        </div>

        <div class="mt-8 text-center">
            <p class="text-xs text-slate-400">
                SAFE &copy; {{ date('Y') }} - Sistema de Autorização e Fluxo Escolar <span class="ml-2 text-blue-400/50">v2.0.0</span>
            </p>
        </div>
    </div>
</div>
