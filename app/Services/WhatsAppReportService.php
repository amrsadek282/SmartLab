<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Report;
use Illuminate\Support\Str;

class WhatsAppReportService
{
    /**
     * Build the prefilled bilingual (Arabic & English) WhatsApp message.
     */
    public static function buildMessage(Order $order, ?string $reportUrl = null): string
    {
        $patientName = $order->patient?->name ?? 'Patient';
        $orderNumber = $order->order_number;

        if (! $reportUrl) {
            $reportUrl = self::getReportUrl($order);
        }

        return "مرحباً {$patientName}،\n\n"
            ."تقرير التحاليل الطبية الخاص بك للطلب رقم {$orderNumber} أصبح جاهزاً.\n\n"
            ."يمكنك استعراض وتحميل التقرير المعتمد من الرابط التالي:\n"
            ."{$reportUrl}\n\n"
            ."شكراً لاختياركم SmartLab — مختبر التحاليل الطبية الذكي.\n\n"
            ."--------------------------------\n\n"
            ."Hello {$patientName},\n\n"
            ."Your laboratory diagnostic report for Order {$orderNumber} is now ready and verified.\n\n"
            ."You can view and download your report using the following secure link:\n"
            ."{$reportUrl}\n\n"
            .'Thank you for choosing SmartLab — Smart Laboratory Information System.';
    }

    /**
     * Get or create the secure public report URL for the given order.
     */
    public static function getReportUrl(Order $order): string
    {
        $report = $order->report;

        if ($report && ! empty($report->share_token)) {
            return route('public.reports.show', ['token' => $report->share_token]);
        }

        // If report exists but has no token, generate and save it
        if ($report && empty($report->share_token)) {
            $token = Str::random(48);
            $report->update(['share_token' => $token]);

            return route('public.reports.show', ['token' => $token]);
        }

        // Fallback if report record is not yet in database
        return route('reports.download', $order);
    }

    /**
     * Generate the complete Click-to-Chat URL for WhatsApp.
     */
    public static function generateClickToChatUrl(Order $order): ?string
    {
        $phone = $order->patient?->phone;
        $normalizedPhone = EgyptianPhoneNormalizer::normalize($phone);

        if (! $normalizedPhone) {
            return null;
        }

        $message = self::buildMessage($order);

        return 'https://wa.me/'.$normalizedPhone.'?text='.urlencode($message);
    }
}
