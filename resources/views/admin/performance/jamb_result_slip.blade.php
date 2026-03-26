<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>JAMB Mock Examination Result Slip</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #000;
            font-size: 12px;
        }

        .page {
            position: relative;
            padding: 1.5cm;
            page-break-after: always;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 0.8cm;
            border-bottom: 2px solid #000;
            padding-bottom: 0.3cm;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
        }

        .header h2 {
            font-size: 13px;
            margin-top: 3px;
        }

        .header .title {
            font-size: 12px;
            margin-top: 4px;
        }

        /* TOP SECTION */
        .top-section {
            display: table;
            width: 100%;
            margin-bottom: 0.8cm;
        }

        .details {
            display: table-cell;
            width: 70%;
            vertical-align: top;
        }

        .student-photo {
            display: table-cell;
            width: 30%;
            text-align: right;
        }

        .student-photo img {
            width: 100px;
            height: 120px;
            border: 1px solid #000;
        }

        .field {
            margin-bottom: 6px;
        }

        .field strong {
            display: inline-block;
            width: 110px;
        }

        /* TABLE */
        .scores table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .scores th {
            background: #000;
            color: #fff;
            padding: 6px;
            font-size: 12px;
            text-align: left;
        }

        .scores td {
            padding: 6px;
            border-bottom: 1px solid #ccc;
        }

        .scores td:last-child {
            text-align: center;
            font-weight: bold;
        }

        /* TOTAL */
        .total {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 8px 0;
        }

        /* REMARKS */
        .remarks {
            margin-top: 10px;
            line-height: 1.6;
        }

        /* FOOTER */
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>

@foreach($reports as $report)

<div class="page">

    <!-- HEADER -->
    <div class="header">
        <h1>JAMB Mock Examination</h1>
        <h2>UTME MOCK RESULT SLIP</h2>

        @if(!empty($centerName))
            <div class="title">Center: {{ $centerName }}</div>
        @else
            <div class="title">Center: All Centers</div>
        @endif
    </div>

    <!-- TOP SECTION -->
    <div class="top-section">

        <div class="details">
            <div class="field"><strong>Full Name:</strong> {{ $report['student']->name }}</div>
            <div class="field"><strong>Candidate ID:</strong> {{ $report['student']->student_id ?? 'N/A' }}</div>
            <div class="field"><strong>Center:</strong> {{ $report['student']->center?->name ?? '—' }}</div>
            <div class="field"><strong>Email:</strong> {{ $report['student']->email ?? 'N/A' }}</div>
        </div>

        <div class="student-photo">
            @if($report['student']->profile_photo_path)
                <img src="{{ asset('storage/' . $report['student']->profile_photo_path) }}">
            @else
                <div style="width:100px;height:120px;border:1px solid #000;display:flex;align-items:center;justify-content:center;">
                    No Photo
                </div>
            @endif
        </div>

    </div>
    <!-- SCORES -->
    <div class="scores">
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Score /100</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report['subject_scores'] as $sub)
                    <tr>
                        <td>{{ $sub->subject->name }}</td>
                        <td>{{ (int)$sub->score_over_100 }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="text-align:center;">No subject performance available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- TOTAL -->
    <div class="total">
        Total Score: {{ $report['total_score'] }}/400
    </div>

    <!-- REMARKS -->
    <div class="remarks">
        <p><strong>Remark:</strong> {{ $report['remark'] }}</p>
        <p><strong>Time spent:</strong> {{ $report['time_spent'] ? $report['time_spent'].' minutes' : 'N/A' }}</p>
        <p><strong>Exam Date:</strong> {{ $report['completed_at']?->format('d/m/Y H:i') ?? 'N/A' }}</p>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Generated on {{ now()->format('d/m/Y H:i') }}
    </div>

</div>

@endforeach

</body>
</html>