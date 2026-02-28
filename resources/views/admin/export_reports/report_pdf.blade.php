<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exam Reports</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #000; }
        .report { width: 100%; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 10px; }
        .brand { color: #111827; font-weight: 700; font-size: 18px; }
        .section-title { font-weight: 700; margin-top: 10px; margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        th, td { border: 2px solid #000; padding: 6px; text-align: left; }
        th { font-weight: 700; }
        .muted { color: #000; opacity: 0.9; }
        .page-break { page-break-after: always; }
        .no-break { page-break-after: avoid; }
        .meta { margin-bottom: 10px; }
    </style>
</head>
<body>
@foreach($reports as $idx => $r)
    <div class="report">
        <div class="header">
            <div class="brand">{{ config('app.name') }} - Exam Report</div>
        </div>

        <h2 class="section-title">Student Details</h2>
        <table>
            <tr>
                <th>Student Name</th>
                <td>{{ $r['student']->name }}</td>
                <th>Student ID</th>
                <td>{{ $r['student']->student_id ?? '-' }}</td>
            </tr>
            <tr>
                <th>Date Submitted</th>
                <td>{{ optional($r['date_submitted'])->format('Y-m-d H:i') ?? '-' }}</td>
                <th>Subjects Taken</th>
                <td>{{ $r['subjects']->isNotEmpty() ? $r['subjects']->join(', ') : '-' }}</td>
            </tr>
        </table>

        <h2 class="section-title">Results Summary</h2>
        <table>
            <tr>
                <th>Total Score</th>
                <td>{{ $r['total_score'] }}</td>
                <th>Percentage</th>
                <td>{{ $r['percentage'] }}%</td>
            </tr>
            <tr>
                <th>Total Questions</th>
                <td>{{ $r['total_questions'] }}</td>
                <th>Correct Answers</th>
                <td>{{ $r['correct_answers'] }}</td>
            </tr>
            <tr>
                <th>Wrong Answers</th>
                <td>{{ $r['wrong_answers'] }}</td>
                <th></th>
                <td></td>
            </tr>
        </table>

        <div class="meta no-break">
            <small>Generated: {{ now()->format('Y-m-d H:i') }}</small>
        </div>
    </div>

    @if($idx + 1 < count($reports))
        <div class="page-break"></div>
    @endif
@endforeach
</body>
</html>
