<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\LessonSchedule;
use App\Models\SchoolClass;

class AttendanceDelayService
{
    /**
     * @param string $scheduledTime HH:mm
     * @param Carbon $realTime
     * @param SchoolClass|null $schoolClass
     * @return array
     */
    public function calculateDelay(string $scheduledTime, Carbon $realTime, ?SchoolClass $schoolClass = null): array
    {
        $scheduled = Carbon::createFromFormat('H:i', $scheduledTime)->setDate($realTime->year, $realTime->month, $realTime->day);
        
        $diffInMinutes = $scheduled->diffInMinutes($realTime, false);

        $hasAbsence = $diffInMinutes > 15;
        
        $impactedLessons = [];
        if ($hasAbsence) {
            // Se houver atraso de mais de 15 minutos, precisamos ver quais aulas foram impactadas
            // Para simplificar, vamos buscar os horários das aulas
            $query = LessonSchedule::query();
            if ($schoolClass) {
                $query->where('school_class_id', $schoolClass->id);
            } else {
                $query->whereNull('school_class_id');
            }
            
            $schedules = $query->orderBy('lesson_number')->get();
            
            foreach ($schedules as $schedule) {
                $lessonStart = Carbon::createFromFormat('H:i:s', $schedule->start_time)->setDate($realTime->year, $realTime->month, $realTime->day);
                $lessonEnd = Carbon::createFromFormat('H:i:s', $schedule->end_time)->setDate($realTime->year, $realTime->month, $realTime->day);
                
                // Se o horário real de entrada for depois do início da aula, houve impacto
                if ($realTime->greaterThan($lessonStart)) {
                    $impactedLessons[] = [
                        'lesson_number' => $schedule->lesson_number,
                        'start_time' => $schedule->start_time,
                        'end_time' => $schedule->end_time,
                        'status' => $realTime->greaterThan($lessonEnd) ? 'falta_nao_justificada' : 'atraso_sem_falta',
                    ];
                }
            }
        }

        return [
            'has_absence' => $hasAbsence,
            'diff_minutes' => $diffInMinutes,
            'impacted_lessons' => $impactedLessons,
            'absence_count' => count(array_filter($impactedLessons, fn($l) => $l['status'] === 'falta_nao_justificada')),
        ];
    }
}
