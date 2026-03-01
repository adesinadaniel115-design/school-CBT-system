<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JAMB Mock Examination Result Slip</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; color: #000; }

        .page { position: relative; page-break-after: always; padding: 2cm; }

        .header { text-align: center; margin-bottom: 1cm; }
        .header h1 { font-size: 22px; font-weight: 700; }
        .header h2 { font-size: 14px; font-weight: 700; margin-top: 0.2cm; padding-top: 1cm; }
        .header .title { font-weight: 700; font-size: 13px; }

        /* Student Photo - Top Right */
        .student-photo { position: absolute; top: 200px; right: 80px; width: 100px; height: 120px; border: 1px solid #000; display: flex; align-items: center; justify-content: center; background: #f5f5f5; overflow: hidden; }
        .student-photo img { width: 100%; height: 100%; object-fit: cover; }

        .info { display: block; margin-bottom: 1cm; }
        .details .field { margin-bottom: 0.25cm; font-size: 12px; }
        .details .field strong { font-weight: 700; }

        .scores table { width: 100%; border-collapse: collapse; font-size: 12px; border: none; }
        .scores th, .scores td { padding: 6px; border: none; }
        .scores th { text-align: left; background: #f5f5f5; }

        .total { text-align: center; font-size: 16px; font-weight: 700; margin: 1cm 0; }

        .remarks { font-size: 11px; line-height: 1.4; }
    </style>
</head>
<body>

@foreach($reports as $report)

<div class="page">
    <!-- Student Photo - Top Right -->
    <div class="student-photo">
        @if($report['student']->profile_photo_path)
            <img src="{{ asset('storage/' . $report['student']->profile_photo_path) }}" alt="Photo">
        @else
            <span style="font-size: 11px;">No Photo</span>
        @endif
    </div>

        .header .title { font-weight: 700; font-size: 16px; text-transform: uppercase; }
        @if(!empty($centerName))
            <h2>Center: {{ $centerName }}</h2>
        @else
            <h2>Center: All centers</h2>
        @endif
    </div>

    <div class="info">
        <div class="details">
            <div class="field"><strong>Full Name:</strong> {{ $report['student']->name }}</div>
            <div class="field"><strong>Candidate ID:</strong> {{ $report['student']->student_id ?? 'N/A' }}</div>
            <div class="field"><strong>Center:</strong> {{ $report['student']->center?->name ?? '—' }}</div>
            <div class="field"><strong>Email:</strong> {{ $report['student']->email ?? 'N/A' }}</div>
        </div>
    </div>

    <div class="scores">
        <table>
            <thead>
                <tr><th>Subject</th><th>Score /100</th></tr>
            </thead>
            <tbody>
                @forelse($report['subject_scores'] as $sub)
                    <tr>
                        <td>{{ $sub->subject->name }}</td>
                        <td style="text-align:center;">{{ (int)$sub->score_over_100 }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="text-align:center;">No subject performance available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="total">Total Score: {{ $report['total_score'] }}/400</div>

    <div class="remarks">
        <p><strong>Remark:</strong> {{ $report['remark'] }}</p>
        <p><strong>Time spent:</strong> {{ $report['time_spent'] ? $report['time_spent'].' minutes' : 'N/A' }}</p>
        <p><strong>Exam Date:</strong> {{ $report['completed_at']?->format('d/m/Y H:i') ?? 'N/A' }}</p>
    </div>

</div>

@endforeach

</body>
</html>
