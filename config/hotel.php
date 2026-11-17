<?php

return [
    'name' => 'THE EAST HOTEL',
    // Tarif dihitung di backend: layanan 10% dari subtotal, pajak 11% dari (subtotal + layanan).
    'service_rate' => 0.10,
    'tax_rate' => 0.11,
    // Reservasi "pending" (menunggu pembayaran simulasi) menahan kamar selama X menit.
    'payment_expiry_minutes' => 60,
    'max_nights' => 30,
];
