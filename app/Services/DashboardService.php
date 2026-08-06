<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Room;
use App\Models\Schedule;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Prepare the data needed to render the dashboard view.
     */
    public function getViewData(?Carbon $today = null): array
    {
        $today = $today ?? Carbon::now('Asia/Manila');
        $monthStart = $today->copy()->startOfMonth();

        return [
            'today' => $today,
            'todayName' => $today->format('l'),
            'ongoing' => $this->getOngoingClasses($today->format('l')),
            'stats' => $this->getStats(),
            'monthStart' => $monthStart,
            'daysInMonth' => $today->daysInMonth,
            'startWeekday' => (int) $monthStart->dayOfWeekIso,
        ];
    }

    /**
     * Retrieve the dashboard summary counts.
     */
    public function getStats(): array
    {
        return [
            'facultyCount' => Faculty::count(),
            'courseCount' => Course::count(),
            'scheduleCount' => Schedule::count(),
            'roomCount' => Room::count(),
        ];
    }

    /**
     * Retrieve classes that are scheduled for the supplied weekday.
     */
    public function getOngoingClasses(string $dayName)
    {
        return Schedule::with(['subject', 'faculty', 'room', 'set'])
            ->where('day', $dayName)
            ->orderBy('start_time')
            ->get();
    }
}
