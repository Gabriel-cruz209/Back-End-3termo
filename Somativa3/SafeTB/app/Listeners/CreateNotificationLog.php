<?php

namespace App\Listeners;

use App\Events\StudentMovementValidated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\NotificationLog;

class CreateNotificationLog
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
            NotificationLog::create([
                'authorization_id' => $authorization->id,
                'student_id' => $student->id,
                'guardian_id' => $guardian->id,
                'channel' => 'log',
                'recipient' => $guardian->email,
                'message' => "Movimentação de {$movement->type} registrada para o aluno {$student->name} às {$movement->occurred_at->format('H:i')}.",
                'status' => 'enviado',
                'sent_at' => now(),
            ]);
        }
    }
}
