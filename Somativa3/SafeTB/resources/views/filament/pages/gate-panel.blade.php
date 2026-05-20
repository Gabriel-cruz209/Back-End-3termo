<x-filament-panels::page>
    <div class="safe-operational-page space-y-7">
        <div class="grid gap-5 lg:grid-cols-2 xl:max-w-5xl">
            <section class="rounded-lg border border-[#dce5f1] bg-white p-7 shadow-[0_16px_34px_rgba(7,26,59,0.08)]">
                <div class="flex items-center justify-between gap-6">
                    <div>
                        <p class="text-base font-semibold text-[#071a3b]">Aguardando Validacao</p>
                        <p class="mt-2 text-4xl font-extrabold leading-none text-[#071a3b]">{{ $this->getPendingCount() }}</p>
                        <p class="mt-3 text-sm font-medium text-[#42577a]">Autorizacoes pendentes</p>
                    </div>
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-amber-100 text-amber-500">
                        <x-heroicon-o-users class="h-9 w-9" />
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-[#dce5f1] bg-white p-7 shadow-[0_16px_34px_rgba(7,26,59,0.08)]">
                <div class="flex items-center justify-between gap-6">
                    <div>
                        <p class="text-base font-semibold text-[#071a3b]">Confirmadas Hoje</p>
                        <p class="mt-2 text-4xl font-extrabold leading-none text-[#071a3b]">{{ $this->getConfirmedTodayCount() }}</p>
                        <p class="mt-3 text-sm font-medium text-[#42577a]">Autorizacoes confirmadas</p>
                    </div>
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                        <x-heroicon-o-check class="h-10 w-10" />
                    </div>
                </div>
            </section>
        </div>

        <section id="autorizacoes" class="overflow-hidden rounded-lg border border-[#dce5f1] bg-white shadow-[0_16px_34px_rgba(7,26,59,0.08)]">
            <header class="border-b border-[#e5edf7] px-7 py-5">
                <h2 class="text-xl font-extrabold tracking-normal text-[#071a3b]">Autorizacoes para Validacao</h2>
            </header>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left">
                    <thead class="bg-[#f8fbff] text-sm font-bold text-[#506078]">
                        <tr>
                            <th class="px-7 py-4">Aluno</th>
                            <th class="px-5 py-4">Tipo</th>
                            <th class="px-5 py-4">Horario</th>
                            <th class="px-5 py-4">Motivo</th>
                            <th class="px-7 py-4">Acoes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e5edf7] text-sm text-[#071a3b]">
                        @forelse ($this->getPendingAuthorizations() as $authorization)
                            <tr class="hover:bg-[#f8fbff]">
                                <td class="px-7 py-4 font-bold">{{ $authorization->student?->name ?? 'Aluno nao informado' }}</td>
                                <td class="px-5 py-4">
                                    <span @class([
                                        'inline-flex rounded-md px-3 py-1 text-sm font-bold',
                                        'bg-emerald-100 text-emerald-700' => $authorization->type === 'entrada',
                                        'bg-red-100 text-red-700' => $authorization->type === 'saida',
                                    ])>
                                        {{ $authorization->type === 'entrada' ? 'Entrada' : 'Saida' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-[#233a62]">
                                    {{ $authorization->authorization_date?->format('d/m/Y') }}
                                    {{ $authorization->scheduled_time }}
                                </td>
                                <td class="px-5 py-4 text-[#233a62]">{{ $authorization->reason ?: 'Sem motivo informado' }}</td>
                                <td class="px-7 py-4">
                                    <div class="flex flex-wrap gap-3">
                                        <button
                                            type="button"
                                            wire:click="confirmAuthorization({{ $authorization->id }})"
                                            class="inline-flex items-center gap-2 rounded-md border border-emerald-400 bg-emerald-50 px-4 py-2 text-sm font-bold text-emerald-700 transition hover:bg-emerald-100"
                                        >
                                            <x-heroicon-o-check-circle class="h-4 w-4" />
                                            Confirmar
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="rejectAuthorization({{ $authorization->id }})"
                                            wire:confirm="Deseja recusar esta autorizacao?"
                                            class="inline-flex items-center gap-2 rounded-md border border-red-400 bg-red-50 px-4 py-2 text-sm font-bold text-red-700 transition hover:bg-red-100"
                                        >
                                            <x-heroicon-o-x-circle class="h-4 w-4" />
                                            Recusar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-7 py-8 text-center text-sm font-semibold text-[#64748b]">
                                    Nenhuma autorizacao aguardando validacao.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <footer class="flex flex-wrap items-center justify-between gap-4 border-t border-[#e5edf7] px-7 py-4 text-sm font-semibold text-[#42577a]">
                <span>Exibindo {{ $this->getPendingAuthorizations()->count() }} de {{ $this->getPendingCount() }} autorizacoes</span>
                <div class="flex gap-2">
                    <span class="rounded-md border border-[#dce5f1] px-3 py-2 text-[#64748b]">1</span>
                    <span class="rounded-md border border-[#dce5f1] px-3 py-2 text-[#64748b]">2</span>
                    <span class="rounded-md border border-[#dce5f1] px-3 py-2 text-[#64748b]">3</span>
                </div>
            </footer>
        </section>

        <section id="historico" class="overflow-hidden rounded-lg border border-[#dce5f1] bg-white shadow-[0_16px_34px_rgba(7,26,59,0.08)]">
            <header class="flex items-center justify-between border-b border-[#e5edf7] px-7 py-5">
                <h2 class="text-xl font-extrabold tracking-normal text-[#071a3b]">Ultimas Validacoes</h2>
                <span class="text-sm font-bold text-[#0057ff]">Ver historico completo</span>
            </header>

            <div class="grid divide-y divide-[#e5edf7] lg:grid-cols-3 lg:divide-x lg:divide-y-0">
                @forelse ($this->getRecentValidations() as $movement)
                    <article class="flex gap-4 px-7 py-5">
                        <div class="mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-full border-2 border-emerald-500 text-emerald-600">
                            <x-heroicon-o-check class="h-5 w-5" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-3">
                                <p class="font-bold text-[#071a3b]">{{ $movement->student?->name ?? 'Aluno nao informado' }}</p>
                                <span @class([
                                    'rounded-md px-3 py-1 text-xs font-bold',
                                    'bg-emerald-100 text-emerald-700' => $movement->type === 'entrada',
                                    'bg-red-100 text-red-700' => $movement->type === 'saida',
                                ])>
                                    {{ $movement->type === 'entrada' ? 'Entrada' : 'Saida' }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-[#233a62]">
                                {{ $movement->occurred_at?->format('d/m/Y H:i') }} - {{ $movement->validator?->name ?? 'Portaria' }}
                            </p>
                            <p class="mt-3 truncate text-sm text-[#42577a]">{{ $movement->authorization?->reason ?: 'Atividade escolar' }}</p>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full px-7 py-8 text-center text-sm font-semibold text-[#64748b]">
                        Nenhuma validacao registrada.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</x-filament-panels::page>
