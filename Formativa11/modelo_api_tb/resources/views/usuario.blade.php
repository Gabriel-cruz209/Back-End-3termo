<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card { background: rgba(255,255,255,0.82); backdrop-filter: blur(16px); }
        .shadow-soft { box-shadow: 0 30px 60px rgba(15, 23, 42, 0.08); }
        .card-animate { transition: transform 0.28s ease, box-shadow 0.28s ease; }
        .card-animate:hover { transform: translateY(-6px); }
        .fade-in-up { opacity: 0; transform: translateY(14px); animation: fadeInUp 0.7s ease-out forwards; }
        .loading-overlay { position: fixed; inset: 0; z-index: 50; display: flex; align-items: center; justify-content: center; background: rgba(15, 23, 42, 0.55); opacity: 0; pointer-events: none; transition: opacity 0.25s ease; }
        .loading-overlay.active { opacity: 1; pointer-events: auto; }
        .spinner { width: 56px; height: 56px; border: 5px solid rgba(255, 255, 255, 0.35); border-top-color: #6366f1; border-radius: 9999px; animation: spin 1s linear infinite; }
        .break-text { word-wrap: break-word; overflow-wrap: break-word; }
        .truncate-text { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-slate-100 text-slate-900">
    <div class="min-h-screen px-4 py-10 sm:px-6 lg:px-8 flex items-center justify-center">
        <div class="glass-card w-full max-w-4xl rounded-[32px] border border-slate-200/70 shadow-soft overflow-hidden">
            <div class="grid gap-8 p-8 lg:grid-cols-[280px_1fr] lg:p-10">
                <div class="flex flex-col items-center gap-6 rounded-[28px] bg-white p-6 shadow-sm card-animate fade-in-up">
                    <img src="{{ $usuario['image'] }}" alt="Avatar de {{ $usuario['firstName'] }}" class="h-40 w-40 rounded-full object-cover border-4 border-indigo-100 shadow-lg transition-transform duration-300 hover:scale-105">
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-slate-950">{{ $usuario['firstName'] }} {{ $usuario['lastName'] }}</p>
                        <p class="mt-2 text-sm font-medium uppercase tracking-[0.24em] text-indigo-600">Perfil do Usuário</p>
                    </div>
                    <div class="space-y-3 w-full rounded-[24px] bg-slate-50 p-4">
                        <div class="flex items-center justify-between text-sm text-slate-500">
                            <span class="font-semibold text-slate-700">Status</span>
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Ativo</span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-slate-500">
                            <span class="font-semibold text-slate-700">Idade</span>
                            <span>{{ $usuario['age'] }} anos</span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-slate-500">
                            <span class="font-semibold text-slate-700">Localização</span>
                            <span>{{ $usuario['address']['city'] }}, {{ $usuario['address']['state'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Informações pessoais</p>
                            <h1 class="mt-3 text-3xl font-semibold text-slate-950">Detalhes do perfil</h1>
                            <p class="mt-2 text-sm text-slate-600">Uma visão limpa e moderna do usuário com navegação simples e foco em informação.</p>
                        </div>
                        <button id="newUserBtn" class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition duration-200 hover:bg-indigo-700 hover:-translate-y-0.5 active:scale-95">
                            <span id="buttonText">Outro usuário</span>
                        </button>
                    </div>

                    <section class="rounded-[28px] bg-white p-6 shadow-sm card-animate transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-center justify-between gap-4 border-b border-slate-200 pb-4">
                            <div>
                                <p class="text-sm uppercase tracking-[0.18em] text-slate-400">Contato</p>
                                <h2 class="mt-2 text-xl font-semibold text-slate-950">Dados principais</h2>
                            </div>
                            <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">ID #{{ $usuario['id'] }}</span>
                        </div>
                        <div class="mt-6 grid gap-4 sm:grid-cols-2 min-w-0">
                            <div class="rounded-[24px] bg-slate-50 p-4 card-animate transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg min-w-0">
                                <p class="text-sm text-slate-500">Email</p>
                                <p class="mt-2 text-base font-medium text-slate-900 break-text">{{ $usuario['email'] }}</p>
                            </div>
                            <div class="rounded-[24px] bg-slate-50 p-4 card-animate transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg min-w-0">
                                <p class="text-sm text-slate-500">Telefone</p>
                                <p class="mt-2 text-base font-medium text-slate-900 break-text">{{ $usuario['phone'] }}</p>
                            </div>
                            <div class="rounded-[24px] bg-slate-50 p-4 card-animate transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg min-w-0">
                                <p class="text-sm text-slate-500">País</p>
                                <p class="mt-2 text-base font-medium text-slate-900 break-text">{{ $usuario['address']['country'] }}</p>
                            </div>
                            <div class="rounded-[24px] bg-slate-50 p-4 card-animate transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg min-w-0">
                                <p class="text-sm text-slate-500">Endereço</p>
                                <p class="mt-2 text-base font-medium text-slate-900 break-text">{{ $usuario['address']['address'] }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-[28px] bg-white p-6 shadow-sm card-animate transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-center justify-between gap-4 border-b border-slate-200 pb-4">
                            <div>
                                <p class="text-sm uppercase tracking-[0.18em] text-slate-400">Perfil</p>
                                <h2 class="mt-2 text-xl font-semibold text-slate-950">Características</h2>
                            </div>
                        </div>
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-[24px] bg-slate-50 p-4 card-animate transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                                <p class="text-sm text-slate-500">Altura</p>
                                <p class="mt-2 text-base font-medium text-slate-900">{{ number_format($usuario['height'], 1) }} cm</p>
                            </div>
                            <div class="rounded-[24px] bg-slate-50 p-4 card-animate transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                                <p class="text-sm text-slate-500">Peso</p>
                                <p class="mt-2 text-base font-medium text-slate-900">{{ number_format($usuario['weight'], 2) }} kg</p>
                            </div>
                            <div class="rounded-[24px] bg-slate-50 p-4 card-animate transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                                <p class="text-sm text-slate-500">Cor do cabelo</p>
                                <p class="mt-2 text-base font-medium text-slate-900">{{ $usuario['hair']['color'] }}</p>
                            </div>
                            <div class="rounded-[24px] bg-slate-50 p-4 card-animate transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                                <p class="text-sm text-slate-500">Tipo de cabelo</p>
                                <p class="mt-2 text-base font-medium text-slate-900">{{ $usuario['hair']['type'] }}</p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <div id="loadingOverlay" class="loading-overlay" aria-hidden="true">
        <div class="flex flex-col items-center gap-4 rounded-[28px] bg-slate-900/90 px-8 py-8 text-center shadow-2xl">
            <div class="spinner"></div>
            <p class="text-sm font-semibold text-white">Carregando novo usuário...</p>
        </div>
    </div>

    <script>
        const button = document.getElementById('newUserBtn');
        const overlay = document.getElementById('loadingOverlay');
        const buttonText = document.getElementById('buttonText');

        button.addEventListener('click', () => {
            overlay.classList.add('active');
            buttonText.textContent = 'Carregando...';
            button.disabled = true;
            setTimeout(() => {
                window.location.reload();
            }, 900);
        });
    </script>
</body>
</html>
