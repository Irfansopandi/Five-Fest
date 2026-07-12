<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventTenant;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminRefundController extends Controller
{
    public function index()
    {

        $perPage = request()->get('perpage', 10);
        $refunds = EventTenant::with(['event', 'tenant', 'event.vendor'])
            ->where('refund_status', 'approved')
            ->latest('refund_approved_at')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage, 'tab' => 'approved']);


        $refundHistory = EventTenant::with(['event', 'tenant', 'event.vendor'])
            ->where('refund_status', 'completed')
            ->latest('refund_completed_at')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage, 'tab' => 'history']);

        return view('admin.refund.index', compact('refunds', 'refundHistory'));
    }

    public function process(EventTenant $eventTenant)
    {
        if ($eventTenant->refund_status !== 'approved') {
            return back()->with('error', 'Status refund tidak valid.');
        }

        // Jika tidak ada midtrans_order_id → langsung tandai completed
        if (!$eventTenant->midtrans_order_id) {
            $eventTenant->update([
                'refund_status'       => 'completed',
                'payment_status'      => 'refunded',
                'refund_completed_at' => now(),
            ]);
            return back()->with('success', 'Refund berhasil diproses.');
        }

        $isProduction = config('midtrans.is_production');
        $serverKey    = config('midtrans.server_key');
        $orderId      = $eventTenant->midtrans_order_id;
        $total        = (int) $eventTenant->event->tenant_booth_price;
        $baseUrl      = $isProduction
                            ? 'https://api.midtrans.com'
                            : 'https://api.sandbox.midtrans.com';

        \Midtrans\Config::$serverKey    = $serverKey;
        \Midtrans\Config::$isProduction = $isProduction;

        // Step 1: Cek status transaksi di Midtrans
        try {
            $statusResponse    = \Midtrans\Transaction::status($orderId);
            $transactionStatus = $statusResponse->transaction_status ?? null;
            $paymentType       = $statusResponse->payment_type ?? null;
        } catch (\Exception $e) {
            $eventTenant->update([
                'refund_status'       => 'completed',
                'payment_status'      => 'refunded',
                'refund_completed_at' => now(),
            ]);
            return back()->with('success', 'Refund berhasil diproses.');
        }

        // Step 2: Transaksi tidak aktif → langsung tandai refunded
        if (in_array($transactionStatus, ['pending', 'expire', 'cancel', 'deny', 'failure'])) {
            $eventTenant->update([
                'refund_status'       => 'completed',
                'payment_status'      => 'refunded',
                'refund_completed_at' => now(),
            ]);
            return back()->with('success', 'Refund berhasil diproses.');
        }

        // Step 3: Credit card → pakai Transaction::refund()
        if ($paymentType === 'credit_card' && $transactionStatus === 'settlement') {
            try {
                \Midtrans\Transaction::refund($orderId, [
                    'refund_amount' => $total,
                    'reason'        => $eventTenant->refund_reason ?? 'Refund disetujui vendor',
                ]);
                $eventTenant->update([
                    'refund_status'       => 'completed',
                    'payment_status'      => 'refunded',
                    'refund_completed_at' => now(),
                ]);
                return back()->with('success', 'Refund berhasil diproses via Midtrans.');
            } catch (\Exception $e) {
                // gagal → lanjut ke step 4
            }
        }

        // Step 4: VA/metode lain → coba cancel dulu
        try {
            \Midtrans\Transaction::cancel($orderId);
            $eventTenant->update([
                'refund_status'       => 'completed',
                'payment_status'      => 'refunded',
                'refund_completed_at' => now(),
            ]);
            return back()->with('success', 'Refund berhasil diproses via Midtrans.');
        } catch (\Exception $e) {
            // gagal → lanjut ke step 5
        }

        // Step 5: Last resort → REST API Midtrans langsung
        try {
            $refundKey = 'REFUND-' . $orderId . '-' . time();
            $response  = Http::withBasicAuth($serverKey, '')
                ->post("{$baseUrl}/v2/{$orderId}/refund", [
                    'refund_key' => $refundKey,
                    'amount'     => $total,
                    'reason'     => $eventTenant->refund_reason ?? 'Refund disetujui vendor',
                ]);

            $body         = $response->json();
            $refundStatus = $body['transaction_status'] ?? null;

            if (in_array($refundStatus, ['refund', 'partial_refund', 'cancel']) || isset($body['refund_chargeback_id'])) {
                $eventTenant->update([
                    'refund_status'       => 'completed',
                    'payment_status'      => 'refunded',
                    'refund_completed_at' => now(),
                ]);
                return back()->with('success', 'Refund berhasil diproses via Midtrans.');
            }

            // ✅ Midtrans tidak support (BCA VA dll) → manual fallback
            $eventTenant->update([
                'refund_status'       => 'completed',
                'payment_status'      => 'refunded',
                'refund_completed_at' => now(),
            ]);
            return back()->with('success', 'Refund berhasil ditandai selesai. Silakan transfer manual ke tenant.');

        } catch (\Exception $e) {
            // ✅ Exception → manual fallback
            $eventTenant->update([
                'refund_status'       => 'completed',
                'payment_status'      => 'refunded',
                'refund_completed_at' => now(),
            ]);
            return back()->with('success', 'Refund berhasil ditandai selesai. Silakan transfer manual ke tenant.');
        }
    }
}