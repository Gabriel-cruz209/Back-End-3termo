<?php

namespace App\Services;

use App\Models\Authorization;
use App\Models\AuthorizationLesson;
use App\Models\StudentMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthorizationValidationService
{
    public function __construct(
        private readonly AttendanceDelayService $delayService,
    ) {}

    public function validateAtGate(Authorization $authorization, User $user): StudentMovement
    {
        return DB::transaction(function () use ($authorization, $user): StudentMovement {
            $now = now();

            if ($authorization->type === 'entrada') {
                $delayInfo = $this->delayService->calculateDelay(
                    (string) $authorization->scheduled_time,
                    $now,
                    $authorization->schoolClass,
                );

                $authorization->update([
                    'has_absence' => $delayInfo['has_absence'],
                    'absence_count' => $delayInfo['absence_count'],
                ]);

                $authorization->lessons()->delete();

                foreach ($delayInfo['impacted_lessons'] as $lesson) {
                    AuthorizationLesson::create([
                        'authorization_id' => $authorization->id,
                        'lesson_number' => $lesson['lesson_number'],
                        'start_time' => $lesson['start_time'],
                        'end_time' => $lesson['end_time'],
                        'status' => $lesson['status'],
                    ]);
                }
            }

            $authorization->update([
                'status' => 'concluida',
                'real_time' => $now,
                'gate_validated_at' => $now,
                'gate_validated_by' => $user->id,
            ]);

            return StudentMovement::create([
                'authorization_id' => $authorization->id,
                'student_id' => $authorization->student_id,
                'type' => $authorization->type,
                'occurred_at' => $now,
                'validated_by' => $user->id,
            ]);
        });
    }
}
