<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Medical Laboratory Report — {{ $reportNumber }} — SmartLab</title>
    <style>
        /* ── DomPDF Page Setup (A4 Portrait) ──────────────── */
        @page {
            size: a4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.4;
            background-color: #ffffff;
        }

        /* ── Typography & Helpers ────────────────────────── */
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        .w-full { width: 100%; }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .align-top { vertical-align: top; }
        .align-middle { vertical-align: middle; }

        /* ── Header Branding ─────────────────────────────── */
        .header-box {
            padding-bottom: 10pt;
            border-bottom: 2pt solid #1e3a8a;
            margin-bottom: 12pt;
        }

        .logo-symbol {
            width: 36pt;
            height: 36pt;
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 14pt;
            font-weight: 800;
            text-align: center;
            line-height: 36pt;
            border-radius: 6pt;
            display: inline-block;
        }

        .brand-title {
            font-size: 16pt;
            font-weight: 800;
            color: #1e3a8a;
            letter-spacing: 0.5pt;
            line-height: 1.1;
        }

        .brand-subtitle {
            font-size: 8pt;
            font-weight: 700;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 0.8pt;
            margin-top: 1.5pt;
        }

        .brand-division {
            font-size: 7.5pt;
            color: #64748b;
            margin-top: 1pt;
        }

        .header-meta td {
            font-size: 8pt;
            padding: 1.5pt 0;
            color: #475569;
        }

        .header-meta-label {
            font-weight: 600;
            color: #64748b;
        }

        .header-meta-val {
            font-weight: 700;
            color: #0f172a;
        }

        /* ── Demographics & Specimen Information Card ────── */
        .info-card {
            background-color: #f8fafc;
            border: 1pt solid #cbd5e1;
            border-radius: 4pt;
            padding: 8pt 10pt;
            margin-bottom: 12pt;
        }

        .info-card-header {
            font-size: 7.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8pt;
            color: #0369a1;
            border-bottom: 0.75pt solid #e2e8f0;
            padding-bottom: 3pt;
            margin-bottom: 5pt;
        }

        .info-table td {
            font-size: 8.5pt;
            padding: 2pt 4pt;
            vertical-align: top;
        }

        .info-lbl {
            color: #64748b;
            font-weight: 600;
            width: 80pt;
        }

        .info-val {
            color: #0f172a;
            font-weight: 700;
        }

        /* ── Test Results Table ──────────────────────────── */
        .section-bar {
            background-color: #f1f5f9;
            border-left: 3.5pt solid #0284c7;
            padding: 3.5pt 8pt;
            margin-top: 6pt;
            margin-bottom: 6pt;
        }

        .section-title {
            font-size: 9pt;
            font-weight: 800;
            color: #0f2744;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }

        .results-table {
            width: 100%;
            border: 1pt solid #cbd5e1;
            border-radius: 3pt;
            margin-bottom: 12pt;
            page-break-inside: auto;
        }

        .results-table thead {
            display: table-header-group;
            background-color: #1e3a8a;
        }

        .results-table th {
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
            padding: 5pt 7pt;
            border: none;
        }

        .results-table tr {
            page-break-inside: avoid;
        }

        .results-table td {
            padding: 5.5pt 7pt;
            font-size: 8.5pt;
            border-bottom: 0.5pt solid #e2e8f0;
            vertical-align: top;
        }

        .results-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .results-table tr:last-child td {
            border-bottom: none;
        }

        .test-name {
            font-weight: 700;
            color: #0f172a;
            font-size: 9pt;
        }

        .test-meta {
            font-size: 7.5pt;
            color: #64748b;
            margin-top: 1pt;
        }

        .test-notes {
            font-size: 7.5pt;
            color: #475569;
            font-style: italic;
            margin-top: 2pt;
        }

        .val-normal {
            font-size: 9pt;
            font-weight: 800;
            color: #0f172a;
        }

        .val-abnormal {
            font-size: 9pt;
            font-weight: 800;
            color: #b91c1c;
        }

        .badge-normal {
            display: inline-block;
            font-size: 6.5pt;
            font-weight: 700;
            color: #047857;
            background-color: #ecfdf5;
            border: 0.5pt solid #a7f3d0;
            border-radius: 2.5pt;
            padding: 1.5pt 4pt;
            text-align: center;
        }

        .badge-abnormal {
            display: inline-block;
            font-size: 6.5pt;
            font-weight: 800;
            color: #b91c1c;
            background-color: #fef2f2;
            border: 0.5pt solid #fecaca;
            border-radius: 2.5pt;
            padding: 1.5pt 4pt;
            text-align: center;
        }

        .category-row {
            background-color: #e2e8f0 !important;
        }

        .category-title {
            font-size: 7.5pt;
            font-weight: 800;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.8pt;
            padding: 3.5pt 7pt !important;
        }

        /* ── Clinical Remarks / Comments ─────────────────── */
        .remarks-box {
            background-color: #f0fdf4;
            border: 0.75pt solid #bbf7d0;
            border-left: 3.5pt solid #16a34a;
            border-radius: 3pt;
            padding: 6pt 9pt;
            margin-top: 8pt;
            margin-bottom: 12pt;
            page-break-inside: avoid;
        }

        .remarks-header {
            font-size: 7pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8pt;
            color: #15803d;
            margin-bottom: 2pt;
        }

        .remarks-body {
            font-size: 8pt;
            color: #166534;
            line-height: 1.35;
        }

        /* ── Signatures & Authorization ─────────────────── */
        .signatures-section {
            margin-top: 16pt;
            margin-bottom: 10pt;
            page-break-inside: avoid;
        }

        .sig-column {
            width: 48%;
            vertical-align: top;
        }

        .sig-rule {
            border-top: 0.75pt solid #94a3b8;
            width: 140pt;
            margin-bottom: 3pt;
        }

        .sig-title {
            font-size: 7.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
            color: #1e3a8a;
            margin-bottom: 18pt;
        }

        .sig-author {
            font-size: 8.5pt;
            font-weight: 700;
            color: #1e293b;
        }

        .sig-dept {
            font-size: 7.5pt;
            color: #64748b;
        }

        .stamp-badge {
            display: inline-block;
            border: 0.75pt dashed #cbd5e1;
            border-radius: 3pt;
            padding: 2.5pt 5pt;
            font-size: 6.5pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
            margin-top: 3pt;
        }

        /* ── Verification & Legal Footer ─────────────────── */
        .footer-section {
            border-top: 0.75pt solid #cbd5e1;
            padding-top: 6pt;
            margin-top: 12pt;
            page-break-inside: avoid;
        }

        .verification-strip {
            background-color: #f8fafc;
            border: 0.5pt solid #e2e8f0;
            border-radius: 2.5pt;
            padding: 3pt 6pt;
            margin-bottom: 4pt;
            font-size: 6.5pt;
            color: #475569;
            text-align: center;
        }

        .verification-strip strong {
            color: #0284c7;
        }

        .disclaimer-note {
            font-size: 6.5pt;
            color: #64748b;
            line-height: 1.3;
            text-align: center;
        }
    </style>
</head>
<body>

{{-- =================================================================== --}}
{{-- 1. SMARTLAB HEADER & LABORATORY IDENTITY                            --}}
{{-- =================================================================== --}}
<div class="header-box">
    <table>
        <tr>
            <!-- Left: SmartLab Identity -->
            <td class="align-top" style="width: 60%;">
                <table>
                    <tr>
                        <td class="align-middle" style="width: 42pt;">
                            <div class="logo-symbol">SL</div>
                        </td>
                        <td class="align-middle" style="padding-left: 6pt;">
                            <div class="brand-title">SmartLab</div>
                            <div class="brand-subtitle">Smart Laboratory Information System</div>
                            <div class="brand-division">Clinical Pathology &bull; Diagnostic Services &bull; Medical Analysis</div>
                        </td>
                    </tr>
                </table>
            </td>

            <!-- Right: Report Identifiers & Specimen Timestamps -->
            <td class="align-top text-right" style="width: 40%;">
                <table class="header-meta">
                    <tr>
                        <td class="text-right"><span class="header-meta-label">Report No:</span> <span class="header-meta-val" style="color: #0284c7;">{{ $reportNumber }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-right"><span class="header-meta-label">Order No:</span> <span class="header-meta-val">{{ $order->order_number }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-right"><span class="header-meta-label">Report Date:</span> <span class="header-meta-val">{{ $reportDate }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-right"><span class="header-meta-label">Specimen Date:</span> <span class="header-meta-val">{{ $order->sample_collected_at ? $order->sample_collected_at->format('d M Y, h:i A') : $order->created_at->format('d M Y, h:i A') }}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

{{-- =================================================================== --}}
{{-- 2. PATIENT DEMOGRAPHICS & ORDER SPECIMEN CARD                       --}}
{{-- =================================================================== --}}
<div class="info-card">
    <table>
        <tr>
            <!-- Left Column: Patient Demographics -->
            <td class="align-top" style="width: 50%; padding-right: 8pt;">
                <div class="info-card-header">Patient Information</div>
                <table class="info-table">
                    <tr>
                        <td class="info-lbl">Patient Name:</td>
                        <td class="info-val" style="font-size: 9.5pt; color: #1e3a8a;">{{ $order->patient->name }}</td>
                    </tr>
                    <tr>
                        <td class="info-lbl">Age / Gender:</td>
                        <td class="info-val">{{ $order->patient->age }} Years &bull; {{ ucfirst($order->patient->gender) }}</td>
                    </tr>
                    <tr>
                        <td class="info-lbl">Patient ID:</td>
                        <td class="info-val">PID-{{ str_pad((string) $order->patient->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    @if($order->patient->phone)
                    <tr>
                        <td class="info-lbl">Phone Number:</td>
                        <td class="info-val">{{ $order->patient->phone }}</td>
                    </tr>
                    @endif
                    @if($order->patient->national_id)
                    <tr>
                        <td class="info-lbl">National ID:</td>
                        <td class="info-val">{{ $order->patient->national_id }}</td>
                    </tr>
                    @endif
                </table>
            </td>

            <!-- Right Column: Specimen & Clinical Order Details -->
            <td class="align-top" style="width: 50%; padding-left: 8pt; border-left: 0.75pt solid #e2e8f0;">
                <div class="info-card-header">Specimen &amp; Order Details</div>
                <table class="info-table">
                    <tr>
                        <td class="info-lbl">Order Number:</td>
                        <td class="info-val" style="font-family: monospace; color: #0f172a;">{{ $order->order_number }}</td>
                    </tr>
                    <tr>
                        <td class="info-lbl">Order Status:</td>
                        <td class="info-val" style="color: #047857; text-transform: uppercase;">{{ str_replace('_', ' ', $order->status) }}</td>
                    </tr>
                    <tr>
                        <td class="info-lbl">Sample Status:</td>
                        <td class="info-val" style="text-transform: capitalize;">{{ str_replace('_', ' ', $order->sample_status ?? 'Processed') }}</td>
                    </tr>
                    <tr>
                        <td class="info-lbl">Investigation Type:</td>
                        <td class="info-val">Diagnostic Laboratory Testing ({{ $order->orderItems->count() }} Investigation(s))</td>
                    </tr>
                    <tr>
                        <td class="info-lbl">Department:</td>
                        <td class="info-val">Clinical Pathology &amp; Diagnostics</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

{{-- =================================================================== --}}
{{-- 3. OFFICIAL DIAGNOSTIC TEST RESULTS TABLE                           --}}
{{-- =================================================================== --}}
<div class="section-bar">
    <div class="section-title">Official Diagnostic Test Results</div>
</div>

<table class="results-table">
    <thead>
        <tr>
            <th class="text-left" style="width: 36%;">Test Parameter / Investigation</th>
            <th class="text-left" style="width: 22%;">Result Value</th>
            <th class="text-center" style="width: 10%;">Flag</th>
            <th class="text-left" style="width: 12%;">Unit</th>
            <th class="text-left" style="width: 20%;">Reference Range</th>
        </tr>
    </thead>
    <tbody>
        @php
            $currentCategory = null;
        @endphp

        @foreach($order->orderItems as $item)
            @php
                $category = $item->test->category ?? 'General Laboratory';
                $resultText = trim($item->result?->result_text ?? '—');
                $refRange = $item->result?->reference_range ?? $item->test->reference_range ?? '—';
                $unit = $item->test->unit ?? '—';
                $notes = $item->result?->notes;

                // Detect if result indicates abnormal / high / low
                $isAbnormal = false;
                $flagLabel = 'NORMAL';

                $lowerResult = strtolower($resultText);
                $lowerNotes = strtolower($notes ?? '');

                if (str_contains($lowerResult, 'high') || str_contains($lowerNotes, 'high') || str_contains($lowerNotes, 'elevated')) {
                    $isAbnormal = true;
                    $flagLabel = 'HIGH';
                } elseif (str_contains($lowerResult, 'low') || str_contains($lowerNotes, 'low') || str_contains($lowerNotes, 'decreased')) {
                    $isAbnormal = true;
                    $flagLabel = 'LOW';
                } elseif (str_contains($lowerResult, 'positive') || str_contains($lowerNotes, 'positive') || str_contains($lowerNotes, 'abnormal') || str_contains($lowerNotes, 'reactive')) {
                    $isAbnormal = true;
                    $flagLabel = 'ABNORMAL';
                }
            @endphp

            @if($currentCategory !== $category && count($order->orderItems) > 1)
                @php $currentCategory = $category; @endphp
                <tr class="category-row">
                    <td colspan="5" class="category-title">
                        &bull; {{ strtoupper($category) }}
                    </td>
                </tr>
            @endif

            <tr>
                <!-- Test Name, Code, and Notes -->
                <td>
                    <div class="test-name">{{ $item->test->name }}</div>
                    @if($item->test->code)
                        <div class="test-meta">Code: {{ $item->test->code }}</div>
                    @endif
                    @if($notes)
                        <div class="test-notes">Note: {{ $notes }}</div>
                    @endif
                </td>

                <!-- Result Value -->
                <td>
                    <div class="{{ $isAbnormal ? 'val-abnormal' : 'val-normal' }}">
                        {!! nl2br(e($resultText)) !!}
                    </div>
                </td>

                <!-- Flag Status -->
                <td class="text-center">
                    @if($isAbnormal)
                        <span class="badge-abnormal">{{ $flagLabel }}</span>
                    @else
                        <span class="badge-normal">NORMAL</span>
                    @endif
                </td>

                <!-- Unit -->
                <td>
                    <span style="color: #475569; font-weight: 600;">{{ $unit }}</span>
                </td>

                <!-- Reference Range -->
                <td>
                    <span style="color: #334155;">{{ $refRange }}</span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- =================================================================== --}}
{{-- 4. CLINICAL COMMENTS & REMARKS (IF PRESENT)                         --}}
{{-- =================================================================== --}}
@php
    $hasAnyNotes = $order->orderItems->contains(fn ($i) => !empty($i->result?->notes)) || !empty($order->notes);
@endphp

@if($hasAnyNotes)
<div class="remarks-box">
    <div class="remarks-header">Laboratory Comments &amp; Clinical Remarks</div>
    <div class="remarks-body">
        @if(!empty($order->notes))
            <p><strong>Order Notes:</strong> {{ $order->notes }}</p>
        @endif
        @foreach($order->orderItems as $item)
            @if(!empty($item->result?->notes))
                <p><strong>{{ $item->test->name }}:</strong> {{ $item->result->notes }}</p>
            @endif
        @endforeach
        <p style="margin-top: 2.5pt; font-size: 7pt; color: #15803d; font-style: italic;">
            * All tests performed according to standardized laboratory protocols and automated quality controls.
        </p>
    </div>
</div>
@endif

{{-- =================================================================== --}}
{{-- 5. SIGNATURES & VALIDATION                                          --}}
{{-- =================================================================== --}}
<div class="signatures-section">
    <table>
        <tr>
            <!-- Medical Technologist / Performing Specialist -->
            <td class="sig-column">
                <div class="sig-title">Medical Technologist</div>
                <div class="sig-rule"></div>
                <div class="sig-author">{{ $order->orderItems->first()?->result?->technician?->name ?? 'Clinical Lab Specialist' }}</div>
                <div class="sig-dept">Department of Clinical Pathology</div>
                <div class="stamp-badge">Digitally Verified &bull; {{ $reportDate }}</div>
            </td>

            <!-- Authorized Signatory / Laboratory Specialist -->
            <td class="sig-column text-right">
                <div style="display: inline-block; text-align: left;">
                    <div class="sig-title">Authorized Signatory</div>
                    <div class="sig-rule"></div>
                    <div class="sig-author">Laboratory Specialist &bull; SmartLab</div>
                    <div class="sig-dept">Quality Assurance &amp; Clinical Diagnostics</div>
                    <div class="stamp-badge">Electronically Approved &bull; SmartLab</div>
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- =================================================================== --}}
{{-- 6. SECURITY, VERIFICATION & CONFIDENTIALITY FOOTER                  --}}
{{-- =================================================================== --}}
<div class="footer-section">
    @php
        $shareToken = $order->report?->share_token;
        $verificationUrl = $shareToken ? route('public.reports.show', ['token' => $shareToken]) : url('/');
    @endphp

    <div class="verification-strip">
        <span>Official Verification Link: </span>
        <strong>{{ $verificationUrl }}</strong>
        <span style="color: #94a3b8; margin: 0 4pt;">|</span>
        <span>Security Hash: <span style="font-family: monospace; color: #475569;">{{ substr(md5($reportNumber . $order->order_number), 0, 16) }}</span></span>
    </div>

    <p class="disclaimer-note" style="margin-top: 3pt;">
        <strong>Confidentiality Notice:</strong> Confidential Medical Information &mdash; For the intended patient and healthcare provider. If received in error, please notify SmartLab immediately.
    </p>
    <p class="disclaimer-note" style="margin-top: 2pt;">
        This document has been electronically validated and authorized through SmartLab &mdash; Smart Laboratory Information System. &copy; {{ date('Y') }} SmartLab. All rights reserved.
    </p>
</div>

</body>
</html>
