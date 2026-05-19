<?php

namespace App\Listeners;

use App\Events\StudentMovementValidated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Models\NotificationLog;

class SendGuardianEmailNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StudentMovementValidated $event): void
    {
        $authorization = $event->authorization;
        $movement = $event->movement;
        $student = $authorization->student;
        
        $guardians = $student->guardians;
        
        foreach ($guardians as $guardian) {
            if (!$guardian->email) continue;

            $message = "Olá {$guardian->name}, o aluno {$student->name} registrou uma {$movement->type} na escola às {$movement->occurred_at->format('H:i')}.";
            
            try {
                Mail::raw($message, function ($mail) use ($guardian, $student, $movement) {
                    $mail->to($guardian->email)
                        ->subject("Notificação de Movimentação Escolar - {$student->name}");
                });

                NotificationLog::create([
                    'authorization_id' => $authorization->id,
                    'student_id' => $student->id,
                    'guardian_id' => $guardian->id,
                    'channel' => 'email',
                    'recipient' => $guardian->email,
                    'message' => $message,
                    'status' => 'enviado',
                    'sent_at' => now(),
                ]);
            } catch (\Exception $e) {
                NotificationLog::create([
                    'authorization_id' => $authorization->id,
                    'student_id' => $student->id,
                    'guardian_id' => $guardian->id,
                    'channel' => 'email',
                    'recipient' => $guardian->email,
                    'message' => $message,
                    'status' => 'erro',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }
    }
}
