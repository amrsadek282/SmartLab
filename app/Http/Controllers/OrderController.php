<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Patient;
use App\Models\Result;
use App\Models\Test;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of laboratory orders.
     */
    public function index(Request $request): View
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $orders = Order::query()
            ->with(['patient', 'invoice', 'creator'])
            ->withCount('orderItems')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, function ($q, $search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('patient', function ($p) use ($search) {
                        $p->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('reception.orders.index', compact('orders', 'status', 'search'));
    }

    /**
     * Show the form for creating a new laboratory order.
     */
    public function create(Request $request): View
    {
        $selectedPatient = null;
        $selectedAppointment = null;

        if ($request->has('patient_id')) {
            $selectedPatient = Patient::find($request->input('patient_id'));
        }

        if ($request->has('appointment_id')) {
            $selectedAppointment = Appointment::with('patient')->find($request->input('appointment_id'));
            if ($selectedAppointment && $selectedAppointment->patient) {
                $selectedPatient = $selectedAppointment->patient;
            }
        }

        $tests = Test::active()->orderBy('category')->orderBy('name')->get();
        $patients = Patient::latest()->limit(50)->get(['id', 'name', 'phone']);

        return view('reception.orders.create', compact('tests', 'patients', 'selectedPatient', 'selectedAppointment'));
    }

    /**
     * Store a newly created order, order items, invoice, and pending results inside a DB Transaction.
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $order = DB::transaction(function () use ($validated, $request) {
            // 1. Generate Unique Order Number
            $datePrefix = now()->format('Ymd');
            $todayCount = Order::whereDate('created_at', today())->count() + 1;
            $orderNumber = 'ORD-'.$datePrefix.'-'.str_pad((string) $todayCount, 4, '0', STR_PAD_LEFT);

            // 2. Create Order
            $order = Order::create([
                'order_number' => $orderNumber,
                'patient_id' => $validated['patient_id'],
                'appointment_id' => $validated['appointment_id'] ?? null,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'created_by' => $request->user()->id,
            ]);

            // 3. Fetch selected tests and create OrderItems + Initial Pending Results
            $tests = Test::whereIn('id', $validated['test_ids'])->get();
            $totalAmount = 0.00;

            foreach ($tests as $test) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'test_id' => $test->id,
                    'price' => $test->price,
                ]);

                // Create initial pending result for technician queue
                Result::create([
                    'order_item_id' => $orderItem->id,
                    'result_text' => null,
                    'reference_range' => $test->reference_range,
                    'status' => 'pending',
                ]);

                $totalAmount += (float) $test->price;
            }

            // 4. Calculate Financial Net Amount & Create Invoice
            $discount = (float) ($validated['discount'] ?? 0.00);
            $netAmount = max(0.00, $totalAmount - $discount);
            $paidAmount = (float) ($validated['paid_amount'] ?? 0.00);

            $paymentStatus = 'unpaid';
            if ($paidAmount >= $netAmount && $netAmount > 0) {
                $paymentStatus = 'paid';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'partially_paid';
            }

            $invoiceNumber = 'INV-'.$datePrefix.'-'.str_pad((string) $todayCount, 4, '0', STR_PAD_LEFT);

            Invoice::create([
                'invoice_number' => $invoiceNumber,
                'order_id' => $order->id,
                'total_amount' => $totalAmount,
                'discount' => $discount,
                'net_amount' => $netAmount,
                'paid_amount' => $paidAmount,
                'payment_status' => $paymentStatus,
                'payment_method' => $validated['payment_method'] ?? null,
            ]);

            // 5. If linked to an appointment, mark appointment as completed
            if (! empty($validated['appointment_id'])) {
                Appointment::where('id', $validated['appointment_id'])->update(['status' => 'completed']);
            }

            return $order;
        });

        return redirect()
            ->route('reception.orders.show', $order)
            ->with('success', "Order {$order->order_number} created successfully.");
    }

    /**
     * Display the specified order with patient, tests, invoice, and results.
     */
    public function show(Order $order): View
    {
        $order->load([
            'patient',
            'orderItems.test',
            'orderItems.result',
            'invoice',
            'creator',
            'appointment',
            'report',
        ]);

        return view('reception.orders.show', compact('order'));
    }

    /**
     * Update status of an order.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed,cancelled'],
        ]);

        $allowedTransitions = [
            'pending' => ['in_progress', 'cancelled'],
            'in_progress' => ['completed'],
        ];

        $allowed = $allowedTransitions[$order->status] ?? [];

        if (! in_array($validated['status'], $allowed)) {
            return redirect()
                ->back()
                ->with('error', "Cannot transition order from '{$order->status}' to '{$validated['status']}'.");
        }

        $order->update(['status' => $validated['status']]);

        return redirect()
            ->back()
            ->with('success', "Order {$order->order_number} status updated to '{$validated['status']}'.");
    }

    /**
     * Update sample collection & lab receipt tracking status.
     */
    public function updateSampleStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'sample_status' => ['required', 'in:pending_collection,collected,received_in_lab'],
        ]);

        $status = $validated['sample_status'];
        $updates = ['sample_status' => $status];

        if ($status === 'collected') {
            $updates['sample_collected_at'] = $order->sample_collected_at ?? now();
        } elseif ($status === 'received_in_lab') {
            $updates['sample_collected_at'] = $order->sample_collected_at ?? now();
            $updates['sample_received_at'] = $order->sample_received_at ?? now();
        } elseif ($status === 'pending_collection') {
            $updates['sample_collected_at'] = null;
            $updates['sample_received_at'] = null;
        }

        $order->update($updates);

        $statusLabels = [
            'pending_collection' => 'Pending Collection',
            'collected' => 'Sample Collected',
            'received_in_lab' => 'Received in Lab',
        ];

        return redirect()
            ->back()
            ->with('success', "Sample status updated to '{$statusLabels[$status]}'.");
    }

    /**
     * Quick inline update of patient phone number directly from the order page.
     */
    public function updatePatientPhone(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'min:8', 'max:25'],
        ]);

        $patient = $order->patient;
        $patient->update([
            'phone' => $validated['phone'],
        ]);

        return redirect()
            ->back()
            ->with('success', "Patient phone number updated to '{$patient->phone}' successfully.");
    }
}
