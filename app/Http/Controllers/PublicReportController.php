<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class PublicReportController extends Controller
{
    /**
     * Display the secure public patient report portal.
     */
    public function show(string $token): View
    {
        $report = Report::where('share_token', $token)->firstOrFail();

        $order = $report->order;

        if ($order->status !== 'completed') {
            abort(403, 'This laboratory report is currently being processed and is not yet ready for viewing.');
        }

        $order->load([
            'patient',
            'orderItems.test',
            'orderItems.result.technician',
            'creator',
            'invoice',
        ]);

        return view('reports.public-show', [
            'report' => $report,
            'order' => $order,
        ]);
    }

    /**
     * Download the official PDF report via secure public token.
     */
    public function download(string $token): Response
    {
        $report = Report::where('share_token', $token)->firstOrFail();
        $order = $report->order;

        if ($order->status !== 'completed') {
            abort(403, 'Report cannot be downloaded until all test results are completed.');
        }

        // Serve cached file if it already exists
        if (Storage::disk('public')->exists($report->pdf_path)) {
            return response()->download(
                Storage::disk('public')->path($report->pdf_path),
                "{$report->report_number}.pdf",
                ['Content-Type' => 'application/pdf']
            );
        }

        // Otherwise generate on the fly
        $order->load([
            'patient',
            'orderItems.test',
            'orderItems.result.technician',
        ]);

        $pdf = Pdf::loadView('reports.pdf', [
            'order' => $order,
            'reportNumber' => $report->report_number,
            'reportDate' => $report->generated_at?->format('d M Y') ?? now()->format('d M Y'),
        ])->setPaper('a4', 'portrait');

        Storage::disk('public')->makeDirectory('reports');
        Storage::disk('public')->put($report->pdf_path, $pdf->output());

        return $pdf->download("{$report->report_number}.pdf");
    }

    /**
     * Stream the PDF report in browser via secure public token.
     */
    public function stream(string $token): Response
    {
        $report = Report::where('share_token', $token)->firstOrFail();
        $order = $report->order;

        if ($order->status !== 'completed') {
            abort(403, 'Report cannot be viewed until all test results are completed.');
        }

        $order->load([
            'patient',
            'orderItems.test',
            'orderItems.result.technician',
        ]);

        $pdf = Pdf::loadView('reports.pdf', [
            'order' => $order,
            'reportNumber' => $report->report_number,
            'reportDate' => $report->generated_at?->format('d M Y') ?? now()->format('d M Y'),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("{$report->report_number}.pdf");
    }
}
