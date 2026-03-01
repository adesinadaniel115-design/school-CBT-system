<!doctype html>
<html><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Performance Report</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color:#000; }
        .report { width:100%; padding:20px; }
        .header { text-align:center; margin-bottom:10px; }
        .brand { font-weight:700; font-size:18px; }
        .section-title { font-weight:700; margin-top:10px; margin-bottom:6px; }
        table { width:100%; border-collapse:collapse; margin-bottom:6px; }
        th, td { border:2px solid #000; padding:6px; text-align:left; }
        th { font-weight:700; }
        .page-break { page-break-after: always; }
        .no-break { page-break-after: avoid; }
        .meta { margin-bottom:10px; }
    </style>
</head><body>
@include('pdf.school_header', ['schoolName' => $schoolName ?? null])
@foreach($reports as $idx => $r)
    <div class="report">
        <div class="header"><div class="brand">{{ config('app.name') }} - Performance Report</div></div>
        <h2 class="section-title">Student Information</h2>
        <table>
            <tr><th>Name</th><td>{{ $r['student']->name }}</td><th>ID</th><td>{{ $r['student']->student_id ?? '-' }}</td></tr>
            <tr><th>Subjects Taken</th><td colspan="3">{{ $r['subjects']->isNotEmpty() ? $r['subjects']->join(', ') : '-' }}</td></tr>
        </table>
        <h2 class="section-title">Performance Summary</h2>
        <table>
            <tr><th>Average Score</th><td>{{ $r['average_score'] }}</td><th>Total Attempts</th><td>{{ $r['total_attempts'] }}</td></tr>
            <tr><th>Highest Score</th><td>{{ $r['highest_score'] }}</td><th>Lowest Score</th><td>{{ $r['lowest_score'] }}</td></tr>
            <tr><th>Overall Percentage</th><td>{{ $r['overall_percentage'] }}%</td><th>Comment</th><td>{{ $r['comment'] }}</td></tr>
        </table>
        <div class="meta no-break"><small>Generated: {{ now()->format('Y-m-d H:i') }}</small></div>
    </div>
    @if($idx + 1 < count($reports))<div class="page-break"></div>@endif
@endforeach
</body></html>