<?php

namespace App\Services;

use App\Models\Authorization;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthorizationNotificationService
{
    public function sendCreated(Authorization $authorization): void
    {
        $authorization->loadMissing([
            'student',
            'schoolClass',
            'course',
            'professorUser',
        ]);

        $message = $this->messageForStaff($authorization);

        if ($authorization->professorUser) {
            $this->sendToUser($authorization, $authorization->professorUser, $message, 'professor');
        }

        if ($authorization->additional_recipient === 'portaria') {
            User::query()
                ->where('role', 'portaria')
                ->where('is_active', true)
                ->each(fn (User $user) => $this->sendToUser($authorization, $user, $message, 'portaria'));
        }
    }

    private function sendToUser(Authorization $authorization, User $user, string $message, string $recipientRole): void
    {
        if ($user->email) {
            try {
                Mail::raw($message, function ($mail) use ($authorization, $user, $recipientRole) {
                    $mail->to($user->email)
                        ->subject("SAFE - Nova autorizacao para {$recipientRole} #{$authorization->id}");
                });
            } catch (\Throwable $exception) {
                Log::error('Falha ao enviar e-mail automatico de autorizacao', [
                    'authorization_id' => $authorization->id,
                    'user_id' => $user->id,
                    'recipient_role' => $recipientRole,
                    'email' => $user->email,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        Log::info('Notificacao automatica de autorizacao criada', [
            'authorization_id' => $authorization->id,
            'user_id' => $user->id,
            'recipient_role' => $recipientRole,
            'recipient' => $user->email,
            'message' => $message,
        ]);
    }

    private function messageForStaff(Authorization $authorization): string
    {
        $studentName = $authorization->student?->name ?? 'Aluno';
        $type = $authorization->type === 'saida' ? 'saida' : 'entrada';
        $date = optional($authorization->authorization_date)->format('d/m/Y') ?? now()->format('d/m/Y');
        $time = $authorization->scheduled_time;
        $className = $authorization->schoolClass?->name ?? 'turma nao informada';
        $reason = $authorization->reason ?: 'motivo nao informado';

        return "[SAFE] Nova autorizacao de {$type} para {$studentName} em {$date} as {$time}. Turma: {$className}. Motivo: {$reason}.";
    }
}
