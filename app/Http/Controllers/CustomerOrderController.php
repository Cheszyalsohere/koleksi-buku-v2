<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Vendor;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return view('customer.order', compact('vendors'));
    }

    public function getMenusByVendor($vendorId)
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => Menu::where('vendor_id', $vendorId)->get(['id', 'nama_menu', 'harga'])
        ]);
    }

    public function getMenuDetail($menuId)
    {
        $menu = Menu::find($menuId);

        if (!$menu) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Menu tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => [
                'id' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'harga' => $menu->harga,
                'deskripsi' => $menu->deskripsi,
            ]
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        try {
            // ========== SIMPAN PESANAN KE DATABASE ==========
            $pesanan = DB::transaction(function () use ($request) {

                // Generate guest_name
                $lastPesanan = Pesanan::orderBy('id', 'desc')->first();
                $nextNumber = $lastPesanan ? $lastPesanan->id + 1 : 1;
                $guestName = 'Guest_' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

                // Generate kode_pesanan: PSN-YYYYMMDD-XXXX
                $kodePesanan = 'PSN-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

                // Buat pesanan
                $pesanan = Pesanan::create([
                    'kode_pesanan' => $kodePesanan,
                    'guest_name' => $guestName,
                    'vendor_id' => $request->vendor_id,
                    'total' => 0,
                    'status_bayar' => 'pending',
                    'snap_token' => null,
                    'midtrans_order_id' => null,
                ]);

                $total = 0;

                foreach ($request->items as $item) {
                    // Ambil data menu dari DB (JANGAN percaya harga dari frontend)
                    $menu = Menu::findOrFail($item['menu_id']);

                    $subtotal = $menu->harga * $item['jumlah'];
                    $total += $subtotal;

                    PesananDetail::create([
                        'pesanan_id' => $pesanan->id,
                        'menu_id' => $menu->id,
                        'nama_menu' => $menu->nama_menu,   // snapshot
                        'harga' => $menu->harga,            // snapshot
                        'jumlah' => $item['jumlah'],
                        'subtotal' => $subtotal,
                    ]);
                }

                // Update total pesanan
                $pesanan->update(['total' => $total]);

                return $pesanan;
            });

            // ========== GENERATE SNAP TOKEN MIDTRANS ==========
            // Dilakukan SETELAH transaction commit, supaya bisa rollback manual kalau Midtrans error

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            // Generate unique order_id untuk Midtrans
            $midtransOrderId = $pesanan->kode_pesanan . '-' . strtoupper(substr(md5(uniqid()), 0, 4));

            // Build item_details dari pesanan_details
            $pesanan->load('details');
            $itemDetails = [];
            foreach ($pesanan->details as $detail) {
                $itemDetails[] = [
                    'id' => (string) $detail->menu_id,
                    'price' => (int) $detail->harga,
                    'quantity' => (int) $detail->jumlah,
                    'name' => substr($detail->nama_menu, 0, 50), // Midtrans max 50 char
                ];
            }

            // Parameter Snap
            $params = [
                'transaction_details' => [
                    'order_id' => $midtransOrderId,
                    'gross_amount' => (int) $pesanan->total,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $pesanan->guest_name,
                    'email' => strtolower($pesanan->guest_name) . '@guest.kantin.test',
                ],
                // Metode pembayaran (QRIS + Bank Transfer untuk kemudahan testing sandbox)
                'enabled_payments' => ['other_qris', 'bca_va', 'bni_va', 'bri_va', 'permata_va', 'gopay'],
            ];

            $snapToken = Snap::getSnapToken($params);

            // Simpan token & order_id ke pesanan
            $pesanan->update([
                'snap_token' => $snapToken,
                'midtrans_order_id' => $midtransOrderId,
            ]);

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Pesanan berhasil dibuat',
                'data' => [
                    'pesanan_id' => $pesanan->id,
                    'kode_pesanan' => $pesanan->kode_pesanan,
                    'guest_name' => $pesanan->guest_name,
                    'total' => $pesanan->total,
                    'snap_token' => $snapToken,
                    'client_key' => config('midtrans.client_key'),
                ]
            ]);

        } catch (\Exception $e) {
            // Kalau gagal generate snap token, hapus pesanan yang barusan dibuat
            if (isset($pesanan) && $pesanan->id) {
                $pesanan->details()->delete();
                $pesanan->delete();
            }

            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Gagal memproses pesanan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook notification dari Midtrans
     */
    public function notification(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notif = new \Midtrans\Notification();

            $orderId = $notif->order_id;
            $statusCode = $notif->status_code;
            $grossAmount = $notif->gross_amount;
            $signatureKey = $notif->signature_key;

            // VERIFIKASI SIGNATURE (security WAJIB)
            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));
            if ($signatureKey !== $expectedSignature) {
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            $transactionStatus = $notif->transaction_status;
            $fraudStatus = $notif->fraud_status ?? null;

            // Cari pesanan berdasarkan midtrans_order_id
            $pesanan = Pesanan::where('midtrans_order_id', $orderId)->first();
            if (!$pesanan) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update status berdasarkan transaction_status dari Midtrans
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($fraudStatus == 'accept' || $fraudStatus === null) {
                    $pesanan->update([
                        'status_bayar' => 'lunas',
                        'paid_at' => now(),
                    ]);
                }
            } elseif ($transactionStatus == 'pending') {
                $pesanan->update(['status_bayar' => 'pending']);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure'])) {
                $pesanan->update(['status_bayar' => 'gagal']);
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Halaman setelah bayar berhasil
     */
    public function paymentSuccess($kodePesanan)
    {
        $pesanan = Pesanan::with('details', 'vendor')
            ->where('kode_pesanan', $kodePesanan)
            ->firstOrFail();

        // Generate QR Code berisi id pesanan (kode_pesanan)
        $qrCode = null;
        if ($pesanan->status_bayar === 'lunas') {
            $qr = new QrCode(data: $pesanan->kode_pesanan, size: 200, margin: 10);
            $writer = new PngWriter();
            $result = $writer->write($qr);
            $qrCode = base64_encode($result->getString());
        }

        return view('customer.payment-success', compact('pesanan', 'qrCode'));
    }

    /**
     * Endpoint AJAX untuk polling status (backup kalau webhook telat)
     */
    public function checkStatus($kodePesanan)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kodePesanan)->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => [
                'status_bayar' => $pesanan->status_bayar,
                'paid_at' => $pesanan->paid_at,
            ]
        ]);
    }

    /**
     * Simulate pembayaran (KHUSUS SANDBOX / TESTING)
     * Langsung update status jadi 'paid' tanpa melalui Midtrans
     */
    public function simulatePay($kodePesanan)
    {
        if (filter_var(config('midtrans.is_production'), FILTER_VALIDATE_BOOLEAN)) {
            abort(403, 'Simulate hanya tersedia di mode sandbox');
        }

        $pesanan = Pesanan::where('kode_pesanan', $kodePesanan)
            ->where('status_bayar', 'pending')
            ->firstOrFail();

        $pesanan->update([
            'status_bayar' => 'lunas',
            'paid_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran berhasil disimulasikan',
        ]);
    }
}
