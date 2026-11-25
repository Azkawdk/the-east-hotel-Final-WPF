@php
/*
 * Skema warna monokrom berbasis brand:
 *   - Positif / Selesai  → brand dark solid  (#1a2332 bg, putih teks)
 *   - Aktif / Proses     → brand gold solid   (#c9a84c bg, gelap teks)
 *   - Netral / Menunggu  → abu-abu tipis      (outline style)
 *   - Negatif / Batal    → gelap transparan   (muted)
 */
$badges = [
  // ── Reservasi ──────────────────────────────────────────
  'pending'        => ['style' => 'border:1.5px solid #c9a84c;color:#8a6210;background:#fffbeb',    'label' => 'Pending'],
  'confirmed'      => ['style' => 'background:#1a2332;color:#fff',                                  'label' => 'Dikonfirmasi'],
  'checked_in'     => ['style' => 'background:#c9a84c;color:#1a2332',                               'label' => 'Check-in'],
  'checked_out'    => ['style' => 'background:#e5e7eb;color:#374151',                               'label' => 'Check-out'],
  'cancelled'      => ['style' => 'background:#f3f4f6;color:#9ca3af;text-decoration:line-through', 'label' => 'Dibatalkan'],

  // ── Pembayaran ─────────────────────────────────────────
  'paid'           => ['style' => 'background:#1a2332;color:#fff',                                  'label' => 'Lunas'],
  'failed'         => ['style' => 'background:#f3f4f6;color:#9ca3af',                              'label' => 'Gagal'],
  'refunded'       => ['style' => 'border:1.5px solid #c9a84c;color:#8a6210;background:#fffbeb',    'label' => 'Refund'],

  // ── Kamar ──────────────────────────────────────────────
  'available'      => ['style' => 'background:#1a2332;color:#fff',                                  'label' => 'Tersedia'],
  'occupied'       => ['style' => 'background:#c9a84c;color:#1a2332',                               'label' => 'Penuh'],
  'dipesan'        => ['style' => 'border:1.5px solid #c9a84c;color:#8a6210;background:#fffbeb',    'label' => 'Dipesan'],
  'maintenance'    => ['style' => 'background:#f3f4f6;color:#374151',                               'label' => 'Perbaikan'],
  'out_of_service' => ['style' => 'background:#f3f4f6;color:#9ca3af',                              'label' => 'Tidak Aktif'],
];

$b = $badges[$status] ?? ['style' => 'background:#f3f4f6;color:#6b7280', 'label' => $status];
@endphp
<span class="badge badge-status" style="font-size:.7rem;padding:.32em .7em;border-radius:.4em;font-weight:600;letter-spacing:.01em;{{ $b['style'] }}">
  {{ $b['label'] }}
</span>
