<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laboratory Report — {{ e($reportNumber) }}</title>
    <style>
        @@page {
            size: a4 portrait;
            margin: 18mm 18mm 18mm 18mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', sans-serif;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9pt;
            color: #222222;
            background-color: #ffffff;
            line-height: 1.45;
        }

        table {
            font-family: 'DejaVu Sans', sans-serif;
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        td, th {
            font-family: 'DejaVu Sans', sans-serif;
        }

        /* ── Header: lab name top-left, meta top-right ── */
        .header-table {
            margin-bottom: 10pt;
        }

        .lab-name {
            font-size: 16pt;
            font-weight: bold;
            color: #1a4a8a;
        }

        .lab-sub {
            font-size: 8pt;
            color: #666666;
            margin-top: 1.5pt;
        }

        .meta-label {
            font-size: 8pt;
            color: #555555;
            text-align: right;
            white-space: nowrap;
            padding-right: 4pt;
            padding-bottom: 1pt;
        }

        .meta-value {
            font-size: 8pt;
            font-weight: bold;
            color: #111111;
            text-align: left;
            white-space: nowrap;
            padding-bottom: 1pt;
        }

        /* ── Horizontal divider below header ── */
        .header-rule {
            border: none;
            border-top: 1pt solid #cccccc;
            margin-bottom: 10pt;
        }

        /* ── Patient info card: light blue bg, left blue border ── */
        .patient-card {
            background-color: #e8eef8;
            border-left: 3pt solid #1a4a8a;
            margin-bottom: 12pt;
        }

        .patient-card td {
            padding: 5pt 10pt;
            font-size: 9pt;
            vertical-align: middle;
        }

        .patient-card .lbl {
            color: #444444;
            width: 55pt;
        }

        .patient-card .val {
            font-weight: bold;
            color: #111111;
            width: 35%;
        }

        /* ── "LABORATORY TEST RESULTS" heading ── */
        .results-heading {
            font-size: 8.5pt;
            font-weight: bold;
            color: #1a4a8a;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
            border-left: 3pt solid #1a4a8a;
            padding-left: 6pt;
            margin-bottom: 8pt;
        }

        /* ── Results table ── */
        .results-table {
            border: 1pt solid #cccccc;
            margin-bottom: 12pt;
        }

        /* Category header rows inside the table */
        .cat-row {
            background-color: #d5e2f5;
        }

        .cat-row td {
            font-size: 9pt;
            font-weight: bold;
            color: #1a4a8a;
            padding: 4pt 10pt;
            border-bottom: 0.5pt solid #b0c4de;
        }

        /* Normal result rows */
        .result-row td {
            padding: 4pt 10pt;
            font-size: 9pt;
            border-bottom: 0.5pt solid #eeeeee;
            vertical-align: middle;
        }

        .result-row:last-child td {
            border-bottom: none;
        }

        .result-label {
            color: #444444;
            width: 38%;
        }

        .result-value {
            font-weight: bold;
            color: #111111;
        }

        .result-value-abnormal {
            font-weight: bold;
            color: #cc0000;
        }

        .result-unit {
            color: #555555;
            font-size: 8pt;
            width: 15%;
        }

        .result-range {
            color: #555555;
            font-size: 8pt;
        }

        /* ── Signatures ── */
        .sig-section {
            margin-top: 22pt;
            margin-bottom: 6pt;
        }

        .sig-title {
            font-size: 8.5pt;
            color: #222222;
            margin-bottom: 18pt;
        }

        .sig-name {
            font-size: 8.5pt;
            color: #111111;
            border-top: 0.75pt solid #444444;
            padding-top: 3pt;
            width: 140pt;
            display: block;
        }

        /* ── Footer ── */
        .footer-note {
            margin-top: 14pt;
            font-size: 7pt;
            color: #888888;
            text-align: center;
        }
    </style>
</head>
<body>

{{-- ================================================================ --}}
{{-- 1. HEADER: Lab Name (left) + Report Meta (right)                  --}}
{{-- ================================================================ --}}
<table class="header-table">
    <tr>
        {{-- Lab identity --}}
        <td style="width: 55%; vertical-align: top;">
            <div class="lab-name">SMARTLAB LABORATORY</div>
            <div class="lab-sub">Clinical Analysis &amp; Pathology Center</div>
        </td>

        {{-- Report meta (right-aligned) --}}
        <td style="width: 45%; vertical-align: top; text-align: right;">
            <table style="width: auto; border-collapse: collapse; margin-left: auto;">
                <tr>
                    <td class="meta-label">Report No:</td>
                    <td class="meta-value">{{ e($reportNumber) }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Order No:</td>
                    <td class="meta-value">{{ e($order->order_number) }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Date:</td>
                    <td class="meta-value">{{ e($reportDate) }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<hr class="header-rule">

{{-- ================================================================ --}}
{{-- 2. PATIENT INFORMATION CARD                                        --}}
{{-- ================================================================ --}}
<table class="patient-card">
    <tr>
        <td class="lbl">Patient:</td>
        <td class="val">{{ e($order->patient->name ?? '—') }}</td>
        <td class="lbl">Age:</td>
        <td class="val">{{ e($order->patient->age ?? '—') }} years</td>
    </tr>
    <tr>
        <td class="lbl">Gender:</td>
        <td class="val">{{ e(ucfirst($order->patient->gender ?? '—')) }}</td>
        <td class="lbl">Phone:</td>
        <td class="val">{{ e($order->patient->phone ?? '—') }}</td>
    </tr>
</table>

{{-- ================================================================ --}}
{{-- 3. TEST RESULTS                                                    --}}
{{-- ================================================================ --}}
<div class="results-heading">Laboratory Test Results</div>

@php
    $currentCategory = null;
@endphp

<table class="results-table">
    @foreach($order->orderItems as $item)
        @php
            $category   = $item->test->category ?? null;
            $resultText = trim($item->result?->result_text ?? '—');
            $unit       = $item->test->unit ?? '';
            $refRange   = $item->result?->reference_range ?? $item->test->reference_range ?? '';
            $notes      = $item->result?->notes ?? '';

            $lowerResult = strtolower($resultText);
            $lowerNotes  = strtolower($notes);
            $isAbnormal  = str_contains($lowerResult, 'high')
                || str_contains($lowerResult, 'low')
                || str_contains($lowerResult, 'positive')
                || str_contains($lowerNotes, 'elevated')
                || str_contains($lowerNotes, 'abnormal')
                || str_contains($lowerNotes, 'reactive');
        @endphp

        {{-- Category header row whenever the category changes --}}
        @if($category && $currentCategory !== $category)
            @php $currentCategory = $category; @endphp
            <tr class="cat-row">
                <td colspan="4">{{ e($category) }}</td>
            </tr>
        @endif

        {{-- Result row: label | value | unit | range --}}
        <tr class="result-row">
            <td class="result-label">{{ e($item->test->name) }}</td>
            <td class="{{ $isAbnormal ? 'result-value-abnormal' : 'result-value' }}">
                {!! nl2br(e($resultText)) !!}
                @if($unit) <span style="font-weight: normal; color: #555555; font-size: 8pt;">{{ e($unit) }}</span>@endif
            </td>
            <td class="result-range" colspan="2">{{ e($refRange) }}</td>
        </tr>

        @if($notes)
            <tr class="result-row">
                <td colspan="4" style="font-size: 7.5pt; color: #666666; font-style: italic; padding-top: 1pt;">
                    Note: {{ e($notes) }}
                </td>
            </tr>
        @endif
    @endforeach
</table>

{{-- ================================================================ --}}
{{-- 4. SIGNATURES                                                      --}}
{{-- ================================================================ --}}
<table class="sig-section">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <div class="sig-title">Medical Technologist</div>
            <span class="sig-name">{{ e($order->orderItems->first()?->result?->technician?->name ?? 'Dr. Ahmed Technician') }}</span>
        </td>
        <td style="width: 50%; vertical-align: top; text-align: right;">
            <div class="sig-title" style="text-align: right;">Laboratory Director</div>
            <span class="sig-name" style="margin-left: auto; display: block; text-align: left;">Dr. Laboratory Director</span>
        </td>
    </tr>
</table>

{{-- ================================================================ --}}
{{-- 5. FOOTER                                                          --}}
{{-- ================================================================ --}}
<div class="footer-note">
    This report is generated electronically and is valid without a physical signature. — SmartLab Laboratory — {{ e($reportDate) }}
</div>

</body>
</html>
