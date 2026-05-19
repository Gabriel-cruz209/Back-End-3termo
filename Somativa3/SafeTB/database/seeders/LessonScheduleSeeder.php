<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schedules = [
            ['lesson_number' => 1, 'start_time' => '13:00', 'end_time' => '13:45', 'duration_minutes' => 45],
            ['lesson_number' => 2, 'start_time' => '13:45', 'end_time' => '14:30', 'duration_minutes' => 45],
            ['lesson_number' => 3, 'start_time' => '14:30', 'end_time' => '15:15', 'duration_minutes' => 45],
            ['lesson_number' => 4, 'start_time' => '15:15', 'end_time' => '16:00', 'duration_minutes' => 45],
            ['lesson_number' => 5, 'start_time' => '16:00', 'end_time' => '16:45', 'duration_minutes' => 45],
        ];

        foreach ($schedules as $schedule) {
            \App\Models\LessonSchedule::create($schedule);
        }
    }
}
