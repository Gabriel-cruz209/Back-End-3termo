<?php

namespace App\Listeners;

use App\Events\StudentMovementValidated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Log;
use App\Models\NotificationLog;

class LogSimulatedWhatsappNotification
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
            $message = "Olá {$guardian->name}, o aluno {$student->name} registrou uma {$movement->type} na escola às {$movement->occurred_at->format('H:i')}.";
            
            Log::info('WhatsApp simulado enviado', [
                'recipient' => $guardian->phone,
                'message' => $message,
            ]);

            NotificationLog::create([
                'authorization_id' => $authorization->id,
                'student_id' => $student->id,
                'guardian_id' => $guardian->id,
                'channel' => 'whatsapp_simulado',
                'recipient' => $guardian->phone,
                'message' => $message,
                'status' => 'simulado',
                'sent_at' => now(),
            ]);
        }
    }
}
