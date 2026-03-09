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
        
        .header {
            text-align: center;
            margin-bottom: 1.25rem;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            background: linear-gradient(120deg, #1e3a8a, #3b82f6);
            color: white;
            position: relative;
        }

        .header h1 {
            margin: 0;
            font-size: 1.5rem;
            letter-spacing: 0.04em;
        }

        .header .institution-name {
            font-weight: 700;
            font-size: 1rem;
            margin-top: 0.25rem;
            opacity: 0.9;
        }

        .header .institution-address {
            font-size: 0.85rem;
            opacity: 0.85;
            margin-top: 0.15rem;
        }

        .header .title {
            font-weight: 700;
            font-size: 0.95rem;
            margin-top: 0.5rem;
            opacity: 0.9;
        }

        .logo {
            position: absolute;
            top: 16px;
            left: 16px;
            width: 60px;
            height: 60px;
            border-radius: 12px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
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
        
        .page-break { page-break-after: always; }    </style>
</head>
<body>

<!-- PROFESSIONAL LAYOUT - updated 2026-03-01; clear view cache if you still see old design -->

@foreach($reports as $idx => $report)

{{-- Watermark temporarily disabled to avoid GD errors
<!-- Watermark with adjustable font size -->
@if($schoolName)
    <div class="watermark" style="font-size: {{ $watermarkFontSize }}px;">{{ $schoolName }}</div>
@endif
--}}

<div class="slip">
    <div class="header">
        @if(!empty($schoolLogoBase64))
            <div class="logo">
                <img src="data:image/png;base64,{{ $schoolLogoBase64 }}" alt="Logo">
            </div>
        @endif

        <h1>JAMB MOCK EXAM RESULT SLIP</h1>

        @if(!empty($schoolName))
            <div class="institution-name">{{ $schoolName }}</div>
        @endif

        @if(!empty($schoolAddress))
            <div class="institution-address">{{ $schoolAddress }}</div>
        @endif

        @php
            $studentCenter = $report['student']->center?->name ?? 'No center';
            $printedCenter = $centerName ?? $studentCenter;
        @endphp
        <div class="title">Center: {{ $printedCenter }}</div>
    </div>

    <!-- Student Photo - Top Right -->
    <div class="student-photo">
        @if($report['student']->profile_photo_path)
            <img src="{{ asset('storage/' . $report['student']->profile_photo_path) }}" alt="Photo">
        @else
            <span style="font-size: 11px;">No Photo</span>
        @endif
    </div>

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
