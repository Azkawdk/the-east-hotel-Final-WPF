@php
$colors = [
  // Reservation
  'pending'    => ['bg'=>'warning', 'label'=>'Pending'],
  'confirmed'  => ['bg'=>'info',    'label'=>'Dikonfirmasi'],
  'checked_in' => ['bg'=>'success', 'label'=>'Check-in'],
  'checked_out'=> ['bg'=>'secondary','label'=>'Check-out'],
  'cancelled'  => ['bg'=>'danger',  'label'=>'Dibatalkan'],
  // Payment
  'paid'       => ['bg'=>'success', 'label'=>'Lunas'],
  'failed'     => ['bg'=>'danger',  'label'=>'Gagal'],
  'refunded'   => ['bg'=>'info',    'label'=>'Refund'],
  // Room
  'available'      => ['bg'=>'success', 'label'=>'Tersedia'],
  'maintenance'    => ['bg'=>'warning', 'label'=>'Perbaikan'],
  'out_of_service' => ['bg'=>'danger',  'label'=>'Tidak Aktif'],
];
$c = $colors[$status] ?? ['bg'=>'secondary','label'=>$status];
@endphp
<span class="badge bg-{{ $c['bg'] }} badge-status">{{ $c['label'] }}</span>
