@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 no-print">
        <div>
            <div class="text-uppercase text-muted small">Timetable</div>
            <h3 class="mb-0">{{ $title }}</h3>
            @isset($subtitle)
                <div class="text-muted small">{{ $subtitle }}</div>
            @endisset
        </div>
        <div class="d-flex flex-wrap gap-2">
            @isset($pdfUrl)
                <a class="btn btn-download" href="{{ $pdfUrl }}" target="_blank" rel="noopener"><i class="bi bi-file-earmark-pdf me-1"></i>Open PDF</a>
            @endisset
        </div>
    </div>

    @isset($setLinks)
        <div class="mb-3 no-print">
            <div class="set-links-row">
                <div class="fw-semibold">View by Set</div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($setLinks as $setLink)
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('timetables.course.set', [$course, $setLink]) }}">
                            {{ $setLink->year_level }}{{ $setLink->year_level === 1 ? 'st' : ($setLink->year_level === 2 ? 'nd' : ($setLink->year_level === 3 ? 'rd' : 'th')) }} Year - {{ $setLink->set_code ?? 'No Set' }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endisset

    <div class="card print-area timetable-card">
        <div class="card-body p-0">
            <div class="print-header">
                <div class="print-title">{{ $title }}</div>
                @isset($subtitle)
                    <div class="print-subtitle">{{ $subtitle }}</div>
                @endisset
            </div>
            <div class="timetable-wrapper">
                <table class="table table-bordered timetable-table mb-0">
                    <colgroup>
                        <col class="time-col">
                        @foreach ($days as $day)
                            <col class="day-col">
                        @endforeach
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="time-col">Time</th>
                            @foreach ($days as $day)
                                <th class="day-col">{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($slots as $index => $slot) 
                                <tr>
                                    <td class="time-col">{{ $slot['label'] }}</td>
                                    @foreach ($days as $day)
                                        @php $cell = $grid[$day][$index]; @endphp
                                        @if ($cell['type'] === 'skip')
                                            @continue
                                        @elseif ($cell['type'] === 'filled')
                                            @php $schedule = $cell['schedule']; @endphp
                                            <td rowspan="{{ $cell['rowspan'] }}" class="timetable-cell filled"
                                                data-tooltip="Subject: {{ $schedule->subject->subject_code }} - {{ $schedule->subject->subject_name }}
Faculty: {{ $schedule->faculty->name }}
Set: {{ $schedule->set->display_name }}
@if($schedule->class_type === 'online')
Mode: Online
@else
Room: {{ $schedule->room ? $schedule->room->building_name . ' ' . $schedule->room->room_name : '' }}
@endif
Day: {{ $schedule->day }}
Time: {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}">
                                                <div class="fw-semibold">{{ $schedule->subject->subject_code }}</div>
                                                <div class="small text-muted">{{ $schedule->subject->subject_name }}</div>
                                                <div class="small">{{ $schedule->set->display_name }}</div>
                                                <div class="small">{{ $schedule->faculty->name }}</div>
                                                <div class="small">
                                                    {{ $schedule->room ? $schedule->room->building_name . ' ' . $schedule->room->room_name : 'Online' }}
                                                </div>
                                            </td>
                                        @else
                                            <td class="timetable-cell empty"></td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
