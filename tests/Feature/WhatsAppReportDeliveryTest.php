<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Patient;
use App\Models\Report;
use App\Models\Result;
use App\Models\Test;
use App\Models\User;
use App\Services\EgyptianPhoneNormalizer;
use App\Services\WhatsAppReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('egyptian phone normalizer accurately formats various egyptian mobile formats', function () {
    expect(EgyptianPhoneNormalizer::normalize('01012345678'))->toBe('201012345678')
        ->and(EgyptianPhoneNormalizer::normalize('01112345678'))->toBe('201112345678')
        ->and(EgyptianPhoneNormalizer::normalize('01212345678'))->toBe('201212345678')
        ->and(EgyptianPhoneNormalizer::normalize('01512345678'))->toBe('201512345678')
        ->and(EgyptianPhoneNormalizer::normalize('+201012345678'))->toBe('201012345678')
        ->and(EgyptianPhoneNormalizer::normalize('00201012345678'))->toBe('201012345678')
        ->and(EgyptianPhoneNormalizer::normalize('010 1234 5678'))->toBe('201012345678')
        ->and(EgyptianPhoneNormalizer::normalize('+20 (10) 1234-5678'))->toBe('201012345678')
        ->and(EgyptianPhoneNormalizer::isValidEgyptianMobile('01012345678'))->toBeTrue()
        ->and(EgyptianPhoneNormalizer::isValidEgyptianMobile('01912345678'))->toBeFalse()
        ->and(EgyptianPhoneNormalizer::getOperator('01012345678'))->toBe('Vodafone')
        ->and(EgyptianPhoneNormalizer::getOperator('01112345678'))->toBe('Etisalat')
        ->and(EgyptianPhoneNormalizer::getOperator('01212345678'))->toBe('Orange')
        ->and(EgyptianPhoneNormalizer::getOperator('01512345678'))->toBe('WE');
});

test('whatsapp message contains patient name, order number, bilingual text, and secure report url', function () {
    $patient = Patient::factory()->create([
        'name' => 'Ahmed Mahmoud',
        'phone' => '01012345678',
    ]);
    $order = Order::factory()->create([
        'patient_id' => $patient->id,
        'order_number' => 'ORD-20260826-0001',
        'status' => 'completed',
    ]);

    $report = Report::create([
        'report_number' => 'REP-20260826-0001',
        'order_id' => $order->id,
        'pdf_path' => 'reports/REP-20260826-0001.pdf',
        'share_token' => 'secure-test-token-12345',
        'generated_by' => $order->created_by,
        'generated_at' => now(),
    ]);

    $order->setRelation('report', $report);

    $message = WhatsAppReportService::buildMessage($order);

    expect($message)->toContain('Ahmed Mahmoud')
        ->toContain('ORD-20260826-0001')
        ->toContain('تقرير التحاليل الطبية الخاص بك')
        ->toContain('Your laboratory diagnostic report')
        ->toContain(route('public.reports.show', ['token' => 'secure-test-token-12345']))
        ->toContain('SmartLab');

    $whatsappUrl = WhatsAppReportService::generateClickToChatUrl($order);
    expect($whatsappUrl)->toStartWith('https://wa.me/201012345678?text=');
});

test('whatsapp link route initiates delivery, records tracking, and redirects to wa.me', function () {
    $technician = User::factory()->create(['role' => 'technician']);
    $patient = Patient::factory()->create(['phone' => '01123456789', 'name' => 'Sara Ibrahim']);
    $order = Order::factory()->create(['patient_id' => $patient->id, 'status' => 'completed']);
    $test = Test::factory()->create(['name' => 'Lipid Profile']);
    $item = OrderItem::create(['order_id' => $order->id, 'test_id' => $test->id, 'price' => 120.00]);

    Result::create([
        'order_item_id' => $item->id,
        'result_text' => 'Normal',
        'status' => 'entered',
        'technician_id' => $technician->id,
    ]);

    $response = $this->actingAs($technician)->get(route('reports.whatsapp', $order));

    $response->assertRedirect();
    $this->assertStringContainsString('https://wa.me/201123456789?text=', $response->headers->get('Location'));

    // Verify report record was created and tracked
    $this->assertDatabaseHas('reports', [
        'order_id' => $order->id,
        'whatsapp_opened_by' => $technician->id,
        'whatsapp_open_count' => 1,
    ]);

    $report = Report::where('order_id', $order->id)->first();
    expect($report->whatsapp_opened_at)->not->toBeNull()
        ->and($report->share_token)->not->toBeEmpty();
});

test('whatsapp link route fails gracefully if patient has no phone number', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $patient = Patient::factory()->create(['phone' => '']);
    $order = Order::factory()->create(['patient_id' => $patient->id, 'status' => 'completed']);

    $response = $this->actingAs($receptionist)->get(route('reports.whatsapp', $order));

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

test('public report portal can be accessed via secure token without login', function () {
    $patient = Patient::factory()->create(['name' => 'Hassan Ali']);
    $order = Order::factory()->create(['patient_id' => $patient->id, 'status' => 'completed']);
    $test = Test::factory()->create(['name' => 'Complete Blood Count (CBC)']);
    $item = OrderItem::create(['order_id' => $order->id, 'test_id' => $test->id, 'price' => 100.00]);

    Result::create([
        'order_item_id' => $item->id,
        'result_text' => 'Hemoglobin: 14.2 g/dL',
        'reference_range' => '13.0 - 17.0',
        'status' => 'entered',
    ]);

    $report = Report::create([
        'report_number' => 'REP-20260826-0099',
        'order_id' => $order->id,
        'pdf_path' => 'reports/REP-20260826-0099.pdf',
        'share_token' => 'token-public-abc-123',
        'generated_by' => $order->created_by,
        'generated_at' => now(),
    ]);

    $response = $this->get(route('public.reports.show', ['token' => 'token-public-abc-123']));

    $response->assertStatus(200);
    $response->assertSee('Hassan Ali');
    $response->assertSee('Complete Blood Count (CBC)');
    $response->assertSee('Hemoglobin: 14.2 g/dL');
    $response->assertSee('Download PDF Report');
});

test('public report portal rejects invalid or non-existent tokens', function () {
    $response = $this->get(route('public.reports.show', ['token' => 'invalid-non-existent-token']));

    $response->assertStatus(404);
});

test('public report download route downloads pdf via valid token', function () {
    $technician = User::factory()->create(['role' => 'technician']);
    $order = Order::factory()->create(['status' => 'completed']);
    $test = Test::factory()->create(['name' => 'Thyroid Stimulating Hormone (TSH)']);
    $item = OrderItem::create(['order_id' => $order->id, 'test_id' => $test->id, 'price' => 80.00]);

    Result::create([
        'order_item_id' => $item->id,
        'result_text' => '2.5 uIU/mL',
        'status' => 'entered',
        'technician_id' => $technician->id,
    ]);

    $report = Report::create([
        'report_number' => 'REP-20260826-0088',
        'order_id' => $order->id,
        'pdf_path' => 'reports/REP-20260826-0088.pdf',
        'share_token' => 'token-tsh-download-999',
        'generated_by' => $technician->id,
        'generated_at' => now(),
    ]);

    $response = $this->get(route('public.reports.download', ['token' => 'token-tsh-download-999']));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

test('order sample status can be transitioned through workflow stages', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $order = Order::factory()->create(['sample_status' => 'pending_collection']);

    // Mark as collected
    $response = $this->actingAs($receptionist)->patch(route('reception.orders.update-sample-status', $order), [
        'sample_status' => 'collected',
    ]);

    $response->assertRedirect();
    $order->refresh();
    expect($order->sample_status)->toBe('collected')
        ->and($order->sample_collected_at)->not->toBeNull();

    // Mark as received in lab
    $response = $this->actingAs($receptionist)->patch(route('reception.orders.update-sample-status', $order), [
        'sample_status' => 'received_in_lab',
    ]);

    $response->assertRedirect();
    $order->refresh();
    expect($order->sample_status)->toBe('received_in_lab')
        ->and($order->sample_received_at)->not->toBeNull();
});

test('patient phone number can be quickly updated from order page', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $patient = Patient::factory()->create(['phone' => '01000000000']);
    $order = Order::factory()->create(['patient_id' => $patient->id]);

    $response = $this->actingAs($receptionist)->patch(route('reception.orders.update-patient-phone', $order), [
        'phone' => '01298765432',
    ]);

    $response->assertRedirect();
    $patient->refresh();
    expect($patient->phone)->toBe('01298765432')
        ->and($order->getNormalizedPatientPhone())->toBe('201298765432');
});
