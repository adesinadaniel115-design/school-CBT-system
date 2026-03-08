<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JAMB Mock Examination Result Slip</title>
    <style>
<<<<<<< HEAD
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
=======
        * { margin:0; padding:0; box-sizing:border-box; }
        html, body { font-family:'DejaVu Sans', Arial, sans-serif; color:#333; background:#fff; line-height:1.5; }
    </style>
    <style>
        /* Professional PDF layout */
        .slip {
            background:#fff;
            max-width:210mm;
            margin:0 auto;
            padding:30px;
            border:none;
            position:relative;
            page-break-inside: avoid;
            box-shadow:none;
        }

        .header {
            text-align:center;
            padding:20px 10px;
            background:#004aad;
            color:#fff;
            margin-bottom:25px;
        }
        .header .institution-name { font-size:14px; margin-bottom:4px; font-weight:400; }
        .header .institution-address { font-size:12px; margin-bottom:6px; }
        .header .title { font-size:24px; font-weight:700; line-height:1.2; }
        .header .subtitle { font-size:14px; font-weight:500; margin-top:4px; letter-spacing:1px; text-transform:uppercase; }

        .student-info-container { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px; }
        .student-details { flex:1; font-size:12px; background:#fafafa; padding:10px; border-radius:4px; }
        .student-details .field { margin-bottom:6px; }
        .student-details .field strong { font-weight:700; }
        .student-photo { width:120px; height:150px; border:1px solid #ccc; overflow:hidden; background:#fafafa; flex-shrink:0; margin-left:12px; }
        .student-photo img { width:100%; height:100%; object-fit:cover; }

        .scores-table { width:100%; border-collapse:collapse; margin:0; }
        .scores-table th, .scores-table td { border:1px solid #ccc; padding:8px; }
        .scores-table th { background:#d8e4fd; text-align:left; }
        .scores-table td { text-align:center; }
        .scores-table .subject-name { text-align:left; }
        .scores-table .total-row { background:#eaeaea; font-weight:700; font-size:14px; border-top:2px solid #004aad; }

        .section { margin:20px 0; }
        .section-title { font-weight:700; font-size:12px; color:#004aad; margin-bottom:8px; text-align:center; }

        .remarks-row { display:flex; margin:6px 0; font-size:11px; }
        .remarks-col { flex:0.5; }
        .remarks-row .field { display:inline-block; }

        /* student info layout: keep details left, photo on the right */
        /* Use a two-column table for absolute placement of photo (dompdf-friendly) */
        .student-info-table { width:100%; border-collapse:collapse; margin-bottom:12px; }
        .student-info-table td.left { vertical-align:top; padding-right:12px; }
        .student-info-table td.right { width:140px; vertical-align:top; text-align:left; padding-left:14px; }
        .student-photo { width:120px; height:120px; border-radius:6px; object-fit:cover; display:block; margin:0; }
        .student-info-left .field-row { margin-bottom:6px; }

        .comment-box { border:1px solid #ccc; padding:8px; font-size:10px; background:#fafafa; }

        .date-generated { text-align:right; font-size:10px; color:#555; margin-top:15px; }
        .page-break { page-break-after:always; }
>>>>>>> origin/backup-main
    </style>
</head>
<body>

<<<<<<< HEAD
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
=======
<!-- PROFESSIONAL LAYOUT - updated 2026-03-01; clear view cache if you still see old design -->

@foreach($reports as $idx => $report)

<div class="slip">

    <!-- Header -->
    <div class="header">
        @if($schoolName)
            <div class="institution-name">{{ $schoolName }}</div>
        @endif
        @if($schoolAddress)
            <div class="institution-address">{{ $schoolAddress }}</div>
        @endif
        <div class="title">JAMB Mock Exam</div>
        <div class="subtitle">UTME Mock Examination Result Slip</div>
        @if(!empty($centerName))
            <div class="institution-address" style="margin-top:4px;">Center: {{ $centerName }}</div>
        @endif
    </div>

    <!-- Candidate Details Section with photo -->
    <div class="section">
        <div class="section-title">Candidate Information</div>
        
        <table class="student-info-table">
            <tr>
                <td class="left">
                    <div class="student-info-left">
                        <div class="details">
                            <div class="field"><strong>Full Name:</strong> {{ $report['student']->name }}</div>
                            <div class="field"><strong>Candidate ID:</strong> {{ $report['student']->student_id ?? 'N/A' }}</div>
                            <div class="field"><strong>Center:</strong> {{ $report['student']->center?->name ?? '—' }}</div>
                            <div class="field"><strong>Email:</strong> {{ $report['student']->email ?? 'N/A' }}</div>
                        </div>
                    </div>
                </td>
                <td class="right">
                    <div class="student-photo">
                        @if($report['student']->profile_photo_path)
                            <img src="{{ asset('storage/' . $report['student']->profile_photo_path) }}" alt="Photo">
                        @else
                            <span style="font-size: 11px;">No Photo</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
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
>>>>>>> origin/backup-main
            </tbody>
        </table>
    </div>

<<<<<<< HEAD
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
=======
    <!-- Performance & Comments Section -->
    <div class="section">
        <div class="section-title">Performance Assessment</div>
        
        <div class="remarks-row">
            <div class="remarks-col">
                <div class="field"><strong>Performance Remark:</strong> {{ $report['remark'] }}</div>
            </div>
        </div>

        <div class="remarks-row">
            <div class="remarks-col">
                <div class="field"><strong>Time Spent:</strong> {{ $report['time_spent'] ? $report['time_spent'] . ' minutes' : 'N/A' }}</div>
            </div>
        </div>

        <div class="remarks-row">
            <div class="remarks-col">
                <div class="field"><strong>Exam Date:</strong> {{ $report['completed_at']?->format('d/m/Y H:i') ?? 'N/A' }}</div>
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
    @if(! $loop->last)
        <div class="page-break"></div>
    @endif
@endforeach

</body>
</html>
>>>>>>> origin/backup-main
