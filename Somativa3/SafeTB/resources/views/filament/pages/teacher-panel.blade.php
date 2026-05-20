<x-filament-panels::page>
    <div class="safe-operational-page space-y-7">
        <div class="grid gap-5 lg:grid-cols-2 xl:max-w-5xl">
            <section class="rounded-lg border border-[#dce5f1] bg-white p-7 shadow-[0_16px_34px_rgba(7,26,59,0.08)]">
                <div class="flex items-center gap-6">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-amber-100 text-amber-500">
                        <x-heroicon-o-clock class="h-9 w-9" />
                    </div>
                    <div>
                        <p class="text-base font-semibold text-[#071a3b]">Pendentes</p>
                        <p class="mt-2 text-4xl font-extrabold leading-none text-[#071a3b]">{{ $this->getPendingCount() }}</p>
                        <p class="mt-3 text-sm font-medium text-[#42577a]">Autorizacoes aguardando sua resposta</p>
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-[#dce5f1] bg-white p-7 shadow-[0_16px_34px_rgba(7,26,59,0.08)]">
                <div class="flex items-center gap-6">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                        <x-heroicon-o-check class="h-10 w-10" />
                    </div>
                    <div>
                        <p class="text-base font-semibold text-[#071a3b]">Respondidas Hoje</p>
                        <p class="mt-2 text-4xl font-extrabold leading-none text-[#071a3b]">{{ $this->getAnsweredTodayCount() }}</p>
                        <p class="mt-3 text-sm font-medium text-[#42577a]">Autorizacoes respondidas hoje</p>
                    </div>
                </div>
            </section>
        </div>

        <section id="autorizacoes" class="overflow-hidden rounded-lg border border-[#dce5f1] bg-white shadow-[0_16px_34px_rgba(7,26,59,0.08)]">
            <header class="border-b border-[#e5edf7] px-7 py-5">
                <h2 class="text-xl font-extrabold tracking-normal text-[#071a3b]">Autorizacoes para Verificacao</h2>
            </header>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[820px] text-left">
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
                                            wire:click="approveAuthorization({{ $authorization->id }})"
                                            class="inline-flex items-center gap-2 rounded-md border border-emerald-400 bg-emerald-50 px-4 py-2 text-sm font-bold text-emerald-700 transition hover:bg-emerald-100"
                                        >
                                            <x-heroicon-o-check-circle class="h-4 w-4" />
                                            Aprovar
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
                                    Nenhuma autorizacao pendente para voce.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section id="historico" class="overflow-hidden rounded-lg border border-[#dce5f1] bg-white shadow-[0_16px_34px_rgba(7,26,59,0.08)]">
            <header class="flex items-center justify-between border-b border-[#e5edf7] px-7 py-5">
                <h2 class="text-xl font-extrabold tracking-normal text-[#071a3b]">Ultimas Respostas</h2>
                <span class="text-sm font-bold text-[#0057ff]">Ver historico completo</span>
            </header>

            <div class="divide-y divide-[#e5edf7]">
                @forelse ($this->getRecentAnswers() as $authorization)
                    <article class="grid gap-4 px-7 py-4 md:grid-cols-[auto_1.2fr_1fr_1.4fr_auto] md:items-center">
                        <div @class([
                            'flex h-10 w-10 items-center justify-center rounded-full',
                            'bg-emerald-100 text-emerald-600' => $authorization->status !== 'recusada_professor',
                            'bg-red-100 text-red-600' => $authorization->status === 'recusada_professor',
                        ])>
                            @if ($authorization->status === 'recusada_professor')
                                <x-heroicon-o-x-mark class="h-6 w-6" />
                            @else
                                <x-heroicon-o-check class="h-6 w-6" />
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-[#071a3b]">{{ $authorization->student?->name ?? 'Aluno nao informado' }}</p>
                            <p class="text-sm text-[#42577a]">{{ $authorization->type === 'entrada' ? 'Entrada' : 'Saida' }}</p>
                        </div>
                        <p class="text-sm text-[#233a62]">{{ $authorization->authorization_date?->format('d/m/Y') }} {{ $authorization->scheduled_time }}</p>
                        <p class="text-sm text-[#42577a]">{{ $authorization->reason ?: 'Sem motivo informado' }}</p>
                        <span @class([
                            'inline-flex justify-center rounded-md px-4 py-2 text-sm font-bold',
                            'bg-emerald-100 text-emerald-700' => $authorization->status !== 'recusada_professor',
                            'bg-red-100 text-red-700' => $authorization->status === 'recusada_professor',
                        ])>
                            {{ $authorization->status === 'recusada_professor' ? 'Recusado' : 'Aprovado' }}
                        </span>
                    </article>
                @empty
                    <div class="px-7 py-8 text-center text-sm font-semibold text-[#64748b]">
                        Nenhuma resposta registrada.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</x-filament-panels::page>
