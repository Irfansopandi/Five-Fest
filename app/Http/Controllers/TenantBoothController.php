<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventTenant;
use Illuminate\Support\Facades\Auth;

class TenantBoothController extends Controller
{
    public function index(Request $request)
    {
        // Fallback update status untuk localhost (karena webhook tidak bisa masuk ke localhost)
        if ($request->has('order_id') && $request->has('transaction_status')) {
            $orderId = $request->order_id;
            $status = $request->transaction_status;
            
            if (strpos($orderId, 'BOOTH-') === 0) {
                $parts = explode('-', $orderId);
                $eventTenantId = $parts[1] ?? null;
                if ($eventTenantId) {
                    $tenant = EventTenant::find($eventTenantId);
                    if ($tenant && $tenant->tenant_id === Auth::id()) {
                        if (in_array($status, ['capture', 'settlement'])) {
                            $tenant->update([
                                'payment_status'     => 'paid',
                                'midtrans_order_id'  => $orderId, // ✅ Fix: simpan order ID
                            ]);
                        }
                    }
                }
            }
            
            // Redirect to event detail page
            if (isset($tenant)) {
                return redirect()->route('event.detail', $tenant->event_id)->with('success', 'Pembayaran berhasil!');
            }
            return redirect()->route('tenant.booths.index');
        }

        $booths = EventTenant::where('tenant_id', Auth::id())
                    ->with('event')
                    ->latest()
                    ->paginate(10);
                    
        return view('tenant.booths', compact('booths'));
    }

    public function pay(EventTenant $eventTenant)
    {
        if ($eventTenant->tenant_id !== Auth::id()) {
            abort(403);
        }

        if ($eventTenant->status !== 'approved') {
            return back()->with('error', 'Pendaftaran booth belum disetujui oleh vendor.');
        }

        if ($eventTenant->payment_status === 'paid') {
            return back()->with('error', 'Booth ini sudah dibayar.');
        }

        // Midtrans setup
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds        = config('midtrans.is_3ds');

        $price            = $eventTenant->event->tenant_booth_price;
        $tenantServiceFee = (int) round($price * 0.03); // Pajak Sewa Tenant 3%
        $totalPrice       = $price + $tenantServiceFee;

        if ($price <= 0) {
            $eventTenant->update(['payment_status' => 'paid']);
            return back()->with('success', 'Booth ini gratis! Pembayaran selesai.');
        }

        $orderId = 'BOOTH-' . $eventTenant->id . '-' . time();
        $params  = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email'      => Auth::user()->email,
            ],
            'item_details' => [
                [
                    'id'       => 'BOOTH-' . $eventTenant->id,
                    'price'    => (int) $price,
                    'quantity' => 1,
                    'name'     => 'Sewa Booth - ' . $eventTenant->event->title,
                ],
                [
                    'id'       => 'TENANT-FEE',
                    'price'    => $tenantServiceFee,
                    'quantity' => 1,
                    'name'     => 'Pajak Sewa Tenant (3%)',
                ],
            ],

            // ✅ Hanya metode yang support refund/cancel di Sandbox Midtrans
            // E-wallet (GoPay, QRIS, ShopeePay) tidak bisa di-refund via API sandbox
            'enabled_payments' => [
                'credit_card',
                'bca_va',
                'bni_va',
                'bri_va',
                'permata_va',
                'mandiri_bill',
                'cimb_va',
                'other_va',
            ],

            'callbacks' => [
                'finish' => route('event.detail', $eventTenant->event_id),
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $eventTenant->update([
                'snap_token'        => $snapToken,
                'midtrans_order_id' => $orderId,
            ]);
            return back()->with('success', 'Silakan selesaikan pembayaran sewa booth Anda.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function requestRefund(Request $request, EventTenant $eventTenant)
    {
        if ($eventTenant->tenant_id !== Auth::id()) {
            abort(403);
        }

        if ($eventTenant->payment_status !== 'paid') {
            return back()->with('error', 'Hanya booth yang sudah dibayar yang dapat di-refund.');
        }

        // cegah ajukan ulang kalau masih pending/approve
        if (in_array($eventTenant->refund_status, ['requested', 'approved'])) {
            return back()->with('error', 'Pengajuan refund sedang diproses.');
        }

        $request->validate([
            'refund_reason' => 'required|string|max:500',
            'refund_bank_name' => 'required|string|max:100',
            'refund_account_number' => 'required|string|max:50',
            'refund_account_name' => 'required|string|max:100',
        ], [
            'refund_reason.required' => 'Alasan refund wajib diisi.',
            'refund_bank_name.required' => 'Nama Bank wajib diisi.',
            'refund_account_number.required' => 'Nomor Rekening wajib diisi.',
            'refund_account_name.required' => 'Nama Pemilik Rekening wajib diisi.',
        ]);

        $eventTenant->update([
            'payment_status' => 'refund_requested',
            'refund_status'  => 'requested',
            'refund_reason'  => $request->refund_reason,
            'refund_bank_name' => $request->refund_bank_name,
            'refund_account_number' => $request->refund_account_number,
            'refund_account_name' => $request->refund_account_name,
            'refund_requested_at' => now(),
            'refund_reject_reason' => null,
        ]);

        return back()->with('success', 'Permintaan refund berhasil diajukan. Menunggu persetujuan vendor.');
    }
}