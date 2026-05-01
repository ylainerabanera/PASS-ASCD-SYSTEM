@php
    $currentClassType = old('class_type', $schedule?->class_type ?? 'face_to_face');
    $currentColor = old('color', $schedule?->color ?? '#e9f4c8');
@endphp

<div class="mb-3">
    <label class="form-label">Subject</label>
    <select id="subject-select" name="subject_id" class="form-select searchable-select" data-searchable="true" required>
        <option value="" disabled hidden @selected(!old('subject_id', $schedule?->subject_id))>Select subject</option>
        @foreach ($subjects as $subject)
            <option value="{{ $subject->id }}" @selected(old('subject_id', $schedule?->subject_id) == $subject->id)>
                {{ $subject->subject_code }} - {{ $subject->subject_name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3 field-compact">
    <label class="form-label">Set</label>
    <select name="set_id" class="form-select searchable-select" data-searchable="true" required>
        <option value="" disabled hidden @selected(!old('set_id', $schedule?->set_id))>Select set</option>
        @foreach ($sets as $set)
            <option value="{{ $set->id }}" data-students="{{ $set->student_count }}" @selected(old('set_id', $schedule?->set_id) == $set->id)>
                {{ $set->display_name }}
            </option>
        @endforeach
    </select>
    <div class="form-text compact-text compact-tight" data-student-count>Students: -</div>
</div>

<div class="mb-3">
    <label class="form-label">Faculty</label>
    <select id="faculty-select" name="faculty_id" class="form-select searchable-select" data-searchable="true" required>
        <option value="" disabled hidden @selected(!old('faculty_id', $schedule?->faculty_id))>Select faculty</option>
        @foreach ($faculties as $faculty)
            <option value="{{ $faculty->id }}" @selected(old('faculty_id', $schedule?->faculty_id) == $faculty->id)>{{ $faculty->name }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Day</label>
    <select name="day" class="form-select" required>
        <option value="" disabled hidden @selected(!old('day', $schedule?->day))>Select day</option>
        @foreach ($days as $day)
            <option value="{{ $day }}" @selected(old('day', $schedule?->day) == $day)>{{ $day }}</option>
        @endforeach
    </select>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Start Time (24h)</label>
        <input type="time" name="start_time" class="form-control"
            value="{{ old('start_time', $schedule?->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '') }}"
            min="08:00" max="20:00" step="900" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">End Time (24h)</label>
        <input type="time" name="end_time" class="form-control"
            value="{{ old('end_time', $schedule?->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '') }}"
            min="08:15" max="20:00" step="900" required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Class Type</label>
    <select name="class_type" class="form-select" data-class-type required>
        <option value="face_to_face" @selected($currentClassType === 'face_to_face')>Face-to-Face</option>
        <option value="online" @selected($currentClassType === 'online')>Online</option>
    </select>
</div>

<div class="mb-3 field-compact" data-room-wrapper>
    <label class="form-label">Room</label>
    <select name="room_id" class="form-select searchable-select" data-searchable="true">
        <option value="" disabled hidden @selected(!old('room_id', $schedule?->room_id))>Select room</option>
        @foreach ($rooms as $room)
            <option value="{{ $room->id }}" data-capacity="{{ $room->capacity }}" @selected(old('room_id', $schedule?->room_id) == $room->id)>
                {{ $room->building_name }} - {{ $room->room_name }}
            </option>
        @endforeach
    </select>
    <div class="form-text compact-text compact-tight" data-room-capacity>Capacity: -</div>
</div>

<div class="mb-3" data-gcode-wrapper>
    <label class="form-label">Gcode</label>
    <input type="text" name="g_code" id="g_code" class="form-control" placeholder="Enter G-code for online class (e.g. Google Meet code)"
        value="{{ old('g_code', $schedule?->g_code) }}">
</div>

<div class="mb-3">
    <label class="form-label">Timetable Color</label>
    <div class="schedule-color-picker">
        <input type="color" name="color" class="form-control form-control-color schedule-color-input"
            value="{{ $currentColor }}" required>
        {{-- <div class="schedule-color-copy">
            Same course/year/set and subject will use this background color in the timetable.
        </div> --}}
    </div>
</div>
