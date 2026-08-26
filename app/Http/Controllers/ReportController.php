<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Report;
use App\Services\EgyptianPhoneNormalizer;
use App\Services\WhatsAppReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    /**
     * Guard: abort if order results are not fully entered.
     */
    private function ensureResultsReady(Order $order): void
    {
        if ($order->status !== 'completed') {
            abort(422, 'Report cannot be generated until all test results have been entered.');
        }
    }

    /**
     * Stream the PDF report in the browser.
     * Generates (or re-generates) the PDF from database data via DOMPDF.
     *
     * Route: GET /reports/{order}/pdf   →   reports.pdf
     */
    public function generatePdf(Order $order): Response
    {
        $this->ensureResultsReady($order);

        $order->load([
            'patient',
            'orderItems.test',
            'orderItems.result.technician',
        ]);

        [$pdf, $reportNumber] = $this->buildPdf($order);

        $this->persistReport($order, $reportNumber, $pdf->output());

        return $pdf->stream("{$reportNumber}.pdf");
    }

    /**
     * Download the PDF report as a file attachment.
     *
     * Route: GET /reports/{order}/download   →   reports.download
     */
    public function download(Order $order): Response
    {
        $this->ensureResultsReady($order);

        $order->load([
            'patient',
            'orderItems.test',
            'orderItems.result.technician',
        ]);

        // Serve cached file if it already exists
        $existing = $order->report;
        if ($existing && Storage::disk('public')->exists($existing->pdf_path)) {
            return response()->download(
                Storage::disk('public')->path($existing->pdf_path),
                "{$existing->report_number}.pdf"
            );
        }

        [$pdf, $reportNumber] = $this->buildPdf($order);

        $this->persistReport($order, $reportNumber, $pdf->output());

        return $pdf->download("{$reportNumber}.pdf");
    }

    /**
     * Build WhatsApp sharing link for the patient, track delivery initiation, and redirect.
     *
     * Route: GET /reports/{order}/whatsapp   →   reports.whatsapp
     */
    public function whatsappLink(Order $order): RedirectResponse
    {
        $this->ensureResultsReady($order);

        $order->load(['patient', 'report']);

        $patientPhone = $order->patient?->phone;
        $normalizedPhone = EgyptianPhoneNormalizer::normalize($patientPhone);

        if (! $normalizedPhone) {
            return redirect()
                ->back()
                ->with('error', 'Patient does not have a valid phone number for WhatsApp delivery. Please update the phone number.');
        }

        // Ensure PDF & report record exist with share token before sending link
        if (! $order->report) {
            $order->load([
                'orderItems.test',
                'orderItems.result.technician',
            ]);
            [$pdf, $reportNumber] = $this->buildPdf($order);
            $this->persistReport($order, $reportNumber, $pdf->output());
            $order->refresh();
        }

        // Track that WhatsApp link has been opened
        if ($order->report) {
            $order->report->update([
                'whatsapp_opened_at' => now(),
                'whatsapp_opened_by' => auth()->id(),
                'whatsapp_open_count' => ($order->report->whatsapp_open_count ?? 0) + 1,
            ]);
        }

        $whatsappUrl = WhatsAppReportService::generateClickToChatUrl($order);

        if (! $whatsappUrl) {
            return redirect()
                ->back()
                ->with('error', 'Unable to generate WhatsApp link for this patient.');
        }

        return redirect()->away($whatsappUrl);
    }

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    /**
     * Render the PDF view via DOMPDF and return [pdf, reportNumber].
     *
     * @return array{0: \Barryvdh\DomPDF\PDF, 1: string}
     */
    private function buildPdf(Order $order): array
    {
        // Reuse existing report number if already generated for this order
        $existing = $order->report;
        if ($existing) {
            $reportNumber = $existing->report_number;
        } else {
            $datePrefix = now()->format('Ymd');
            $todayCount = Report::whereDate('created_at', today())->count() + 1;
            $reportNumber = 'REP-'.$datePrefix.'-'.str_pad((string) $todayCount, 4, '0', STR_PAD_LEFT);
        }

        $pdf = Pdf::loadView('reports.pdf', [
            'order' => $order,
            'reportNumber' => $reportNumber,
            'reportDate' => now()->format('d M Y'),
        ])->setPaper('a4', 'portrait');

        return [$pdf, $reportNumber];
    }

    /**
     * Save PDF file to public disk and upsert the Report database record.
     */
    private function persistReport(Order $order, string $reportNumber, string $pdfContent): void
    {
        Storage::disk('public')->makeDirectory('reports');
        $path = "reports/{$reportNumber}.pdf";
        Storage::disk('public')->put($path, $pdfContent);

        $existing = $order->report;
        $shareToken = $existing?->share_token ?? Str::random(48);

        Report::updateOrCreate(
            ['order_id' => $order->id],
            [
                'report_number' => $reportNumber,
                'pdf_path' => $path,
                'share_token' => $shareToken,
                'generated_by' => auth()->id() ?? $order->created_by,
                'generated_at' => now(),
            ]
        );
    }
}
