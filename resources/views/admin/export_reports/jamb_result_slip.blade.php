<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JAMB UTME Result Slip</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; color: #000; line-height: 1.4; }
        
        .slip { width: 100%; max-width: 800px; margin: 0 auto; padding: 20px; min-height: 100vh; position: relative; }
        
        /* Logo - Top Left */
        .logo { position: absolute; top: 20px; left: 20px; width: 70px; height: 70px; }
        .logo img { width: 100%; height: 100%; object-fit: contain; }
        
        /* Student Photo - Top Right */
        .student-photo { position: absolute; top: 200px; right: 80px; width: 100px; height: 120px; border: 1px solid #000; display: flex; align-items: center; justify-content: center; background: #f5f5f5; overflow: hidden; }
        .student-photo img { width: 100%; height: 100%; object-fit: cover; }
        
        
        /* Watermark */
        .watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); color: #ccc; opacity: 0.15; z-index: -1; white-space: nowrap; pointer-events: none; }
        
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #000; padding-bottom: 10px; padding-top: 80px; }
        .institution-name { font-weight: 700; font-size: 14px; margin-bottom: 3px; }
        .institution-address { font-size: 11px; color: #333; margin-bottom: 8px; }
        .title { font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; margin: 10px 0; }
        
        .section { margin: 15px 0; }
        .section-title { font-weight: 700; font-size: 11px; text-transform: uppercase; margin: 10px 0 8px 0; border-bottom: 2px solid #000; padding-bottom: 4px; }
        
        table { width: 100%; border-collapse: collapse; margin: 8px 0; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; font-size: 10px; }
        th { font-weight: 700; background: #f5f5f5; }
        
        .info { display: block; margin-bottom: 1cm; }
        .details .field { margin-bottom: 0.25cm; font-size: 12px; }
        .details .field strong { font-weight: 700; }
        
        .scores-table { width: 100%; }
        .scores-table th, .scores-table td { border: 1px solid #000; padding: 8px; text-align: center; }
        .subject-name { text-align: left; }
        
        .total-row { font-weight: 700; background: #f5f5f5; }
        
        .remarks-row { display: flex; margin: 6px 0; font-size: 11px; }
        .remarks-col { flex: 0.5; }
        
        .comment-box { margin: 8px 0; padding: 8px; border: 1px solid #000; font-size: 10px; line-height: 1.4; }
        
        .date-generated { text-align: right; font-size: 10px; margin-top: 15px; }
        
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

@foreach($reports as $idx => $report)

{{-- Watermark temporarily disabled to avoid GD errors
<!-- Watermark with adjustable font size -->
@if($schoolName)
    <div class="watermark" style="font-size: {{ $watermarkFontSize }}px;">{{ $schoolName }}</div>
@endif
--}}

<div class="slip">

    <!-- Student Photo - Top Right -->
    <div class="student-photo">
        @if($report['student']->profile_photo_path)
            <img src="{{ asset('storage/' . $report['student']->profile_photo_path) }}" alt="Photo">
        @else
            <span style="font-size: 11px;">No Photo</span>
        @endif
    </div>

    <!-- Header -->
    <div class="header">
        @if($schoolName)
            <div class="institution-name">{{ $schoolName }}</div>
        @endif
        @if($schoolAddress)
            <div class="institution-address">{{ $schoolAddress }}</div>
        @endif
        <div class="title">JAMB UTME Mock Examination Result Slip</div>
        @if(!empty($centerName))
            <div class="institution-address">Center: {{ $centerName }}</div>
        @endif
    </div>

    <!-- Candidate Details Section -->
    <div class="section">
        <div class="section-title">Candidate Information</div>
        
        <div class="info">
            <div class="details">
                <div class="field"><strong>Full Name:</strong> {{ $report['student']->name }}</div>
                <div class="field"><strong>Candidate ID:</strong> {{ $report['student']->student_id ?? 'N/A' }}</div>
                <div class="field"><strong>Center:</strong> {{ $report['student']->center?->name ?? '—' }}</div>
                <div class="field"><strong>Email:</strong> {{ $report['student']->email ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Subject Scores Section -->
    <div class="section">
        <div class="section-title">Subject Scores</div>
        
        <table class="scores-table">
            <thead>
                <tr>
                    <th class="subject-name">Subject</th>
                    <th>Score /100</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report['subject_scores'] as $subScore)
                <tr>
                    <td class="subject-name">{{ $subScore->subject->name }}</td>
                    <td>{{ (int)$subScore->score_over_100 }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align:center;">No subject scores available</td>
                </tr>
                @endforelse
                
                <!-- Padding rows if less than 4 subjects -->
                @for($i = count($report['subject_scores']); $i < 4; $i++)
                <tr>
                    <td class="subject-name">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @endfor
                
                <tr class="total-row">
                    <td>Total Score</td>
                    <td>{{ $report['total_score'] }}/400</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Performance & Comments Section -->
    <div class="section">
        <div class="section-title">Performance Assessment</div>
        
        <div class="remarks-row">
            <div class="remarks-col">
                <div class="label">Performance Remark</div>
                <div class="value">{{ $report['remark'] }}</div>
            </div>
        </div>

        <div class="remarks-row">
            <div class="remarks-col">
                <div class="label">Time Spent</div>
                <div class="value">{{ $report['time_spent'] ? $report['time_spent'] . ' minutes' : 'N/A' }}</div>
            </div>
        </div>

        <div class="remarks-row">
            <div class="remarks-col">
                <div class="label">Exam Date</div>
                <div class="value">{{ $report['completed_at']?->format('d/m/Y H:i') ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="section-title" style="margin-top: 12px;">General Comment</div>
        <div class="comment-box">
            {{ $report['comment'] }}
        </div>
    </div>

    <!-- Date Generated -->
    <div class="date-generated">
        Generated: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</div>
@endforeach

</body>
</html>
