<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Authorizations\AuthorizationResource;
use App\Models\Authorization;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestAuthorizations extends TableWidget
{
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Últimas Autorizações')
            ->query(
                Authorization::query()
                    ->with(['student'])
                    ->latest('updated_at')
                    ->limit(5),
            )
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->formatStateUsing(fn ($state): string => "#{$state}"),
                TextColumn::make('student.name')
                    ->label('Aluno')
                    ->weight('semibold')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'entrada' => 'success',
                        'saida' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title()->toString())
                    ->color(fn (string $state): string => match ($state) {
                        'aguardando_professor', 'aguardando_portaria' => 'warning',
                        'recusada_professor', 'cancelada' => 'danger',
                        'validada_portaria', 'concluida' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('scheduled_time')
                    ->label('Horário'),
            ])
            ->recordUrl(fn (Authorization $record): string => AuthorizationResource::getUrl('edit', ['record' => $record]))
            ->paginated(false);
    }
}
