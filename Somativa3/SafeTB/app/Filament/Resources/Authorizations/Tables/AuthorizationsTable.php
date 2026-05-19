<?php

namespace App\Filament\Resources\Authorizations\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\StudentMovement;
use App\Events\StudentMovementValidated;
use App\Services\AttendanceDelayService;
use App\Models\AuthorizationLesson;
use Filament\Forms\Components\Textarea;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class AuthorizationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('student.name')
                    ->label('Aluno')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'entrada' => 'success',
                        'saida' => 'warning',
                    }),
                TextColumn::make('schoolClass.name')
                    ->label('Turma')
                    ->searchable(),
                TextColumn::make('course.name')
                    ->label('Curso')
                    ->searchable(),
                TextColumn::make('teacher.name')
                    ->label('Professor')
                    ->searchable(),
                TextColumn::make('authorization_date')
                    ->label('Data')
                    ->date()
                    ->sortable(),
                TextColumn::make('scheduled_time')
                    ->label('Previsto'),
                TextColumn::make('real_time')
                    ->label('Real')
                    ->dateTime(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'rascunho' => 'gray',
                        'aguardando_professor' => 'info',
                        'aprovada_professor' => 'success',
                        'recusada_professor' => 'danger',
                        'aguardando_portaria' => 'warning',
                        'validada_portaria' => 'success',
                        'concluida' => 'success',
                        'cancelada' => 'danger',
                    }),
                TextColumn::make('absence_count')
                    ->label('Faltas'),
                TextColumn::make('createdBy.name')
                    ->label('Criado por'),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'entrada' => 'Entrada',
                        'saida' => 'Saída',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'rascunho' => 'Rascunho',
                        'aguardando_professor' => 'Aguardando Professor',
                        'aprovada_professor' => 'Aprovada Professor',
                        'recusada_professor' => 'Recusada Professor',
                        'aguardando_portaria' => 'Aguardando Portaria',
                        'validada_portaria' => 'Validada Portaria',
                        'concluida' => 'Concluída',
                        'cancelada' => 'Cancelada',
                    ]),
                SelectFilter::make('school_class_id')
                    ->relationship('schoolClass', 'name')
                    ->label('Turma'),
            ])
            ->actions([
                Action::make('approve_professor')
                    ->label('Aprovar Professor')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn ($record) => $record->status === 'aguardando_professor' && (auth()->user()->role === 'professor' || auth()->user()->role === 'admin'))
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'aguardando_portaria',
                            'teacher_validated_at' => now(),
                            'teacher_validated_by' => auth()->id(),
                        ]);
                        Notification::make()
                            ->title('Autorização aprovada pelo professor')
                            ->success()
                            ->send();
                    }),
                Action::make('reject_professor')
                    ->label('Recusar Professor')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn ($record) => $record->status === 'aguardando_professor' && (auth()->user()->role === 'professor' || auth()->user()->role === 'admin'))
                    ->form([
                        Textarea::make('cancellation_reason')
                            ->label('Motivo da recusa')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'recusada_professor',
                            'teacher_validated_at' => now(),
                            'teacher_validated_by' => auth()->id(),
                            'cancellation_reason' => $data['cancellation_reason'],
                        ]);
                        Notification::make()
                            ->title('Autorização recusada pelo professor')
                            ->danger()
                            ->send();
                    }),
                Action::make('validate_gate')
                    ->label('Validar Portaria')
                    ->color('info')
                    ->icon('heroicon-o-shield-check')
                    ->visible(fn ($record) => $record->status === 'aguardando_portaria' && (auth()->user()->role === 'portaria' || auth()->user()->role === 'admin'))
                    ->action(function ($record, AttendanceDelayService $delayService) {
                        $now = now();
                        
                        // Lógica de cálculo de atraso se for entrada
                        if ($record->type === 'entrada') {
                            $delayInfo = $delayService->calculateDelay($record->scheduled_time, $now, $record->schoolClass);
                            $record->update([
                                'has_absence' => $delayInfo['has_absence'],
                                'absence_count' => $delayInfo['absence_count'],
                            ]);

                            foreach ($delayInfo['impacted_lessons'] as $lesson) {
                                AuthorizationLesson::create([
                                    'authorization_id' => $record->id,
                                    'lesson_number' => $lesson['lesson_number'],
                                    'start_time' => $lesson['start_time'],
                                    'end_time' => $lesson['end_time'],
                                    'status' => $lesson['status'],
                                ]);
                            }
                        }

                        $record->update([
                            'status' => 'concluida',
                            'real_time' => $now,
                            'gate_validated_at' => $now,
                            'gate_validated_by' => auth()->id(),
                        ]);

                        $movement = StudentMovement::create([
                            'authorization_id' => $record->id,
                            'student_id' => $record->student_id,
                            'type' => $record->type,
                            'occurred_at' => $now,
                            'validated_by' => auth()->id(),
                        ]);

                        event(new StudentMovementValidated($record, $movement));

                        Notification::make()
                            ->title('Movimentação validada na portaria')
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
