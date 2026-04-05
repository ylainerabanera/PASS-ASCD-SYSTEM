@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Faculty Load Summary</h3>
            {{-- <div class="text-muted small">Total teaching hours by day and overall. Units are counted per subject + set.</div> --}}
            <div class="text-muted small">Total teaching hours by day and overall. Units are counted per subject.</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Faculty</th>
                        <th>Total Hours</th>
                        <th>Total Units</th>
                        @foreach ($days as $day)
                            <th>{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row['faculty']->name }}</td>
                            <td>{{ $row['totalHours'] }}</td>
                            <td>{{ $row['units'] }}</td>
                            @foreach ($days as $day)
                                <td>{{ $row['perDay'][$day] }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 4 + count($days) }}" class="text-muted">No data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
