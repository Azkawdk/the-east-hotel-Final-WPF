<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use App\Services\ReservationService;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        // Akun demo - ganti kata sandi sebelum dipakai di luar lokal.
        User::updateOrCreate(['email' => 'admin@theeasthotel.test'], ['name' => 'Admin The East', 'password' => 'password123', 'role' => 'admin', 'is_active' => true]);
        User::updateOrCreate(['email' => 'staf@theeasthotel.test'], ['name' => 'Staf Resepsionis', 'password' => 'password123', 'role' => 'staff', 'is_active' => true]);

        $amen = [];
        $list = [
            ['Kolam renang tanpa batas', 'hotel', 'Kolam di tepi tebing menghadap Samudra Hindia'],
            ['Spa & wellness', 'hotel', 'Pijat tradisional Bali dan ritual mandi bunga'],
            ['Restoran tepi laut', 'hotel', 'Masakan Bali dan Nusantara dengan bahan lokal'],
            ['Antar-jemput bandara', 'hotel', 'Tersedia dengan reservasi sebelumnya'],
            ['Taman tropis', 'hotel', 'Jalur jalan kaki di antara kebun frangipani'],
            ['Pendingin ruangan', 'room', null],
            ['Wi-Fi cepat', 'room', null],
            ['Balkon pribadi', 'room', null],
            ['Kolam renang pribadi', 'room', null],
            ['Bathtub outdoor', 'room', null],
            ['Sarapan harian', 'room', null],
            ['Dapur kecil', 'room', null],
        ];
        foreach ($list as [$n, $s, $d]) {
            $amen[$n] = Amenity::firstOrCreate(['name' => $n], ['scope' => $s, 'description' => $d]);
        }

        $types = [
            ['Deluxe Garden', 'Kamar lapang dengan taman tropis pribadi, tempat tidur king, dan bathtub batu alam.', 2, 1450000, 42, 'King', ['Pendingin ruangan', 'Wi-Fi cepat', 'Balkon pribadi', 'Sarapan harian'], ['101', '102', '103', '104', '105', '106'], 1],
            ['Ocean View Suite', 'Suite dengan ruang tamu terpisah dan balkon menghadap laut, tempat menyaksikan matahari terbit.', 3, 2350000, 68, 'King + sofa bed', ['Pendingin ruangan', 'Wi-Fi cepat', 'Balkon pribadi', 'Bathtub outdoor', 'Sarapan harian'], ['201', '202', '203', '204', '205'], 2],
            ['Private Pool Villa', 'Vila satu kamar dengan kolam renang pribadi, paviliun bale, dan taman berpagar.', 3, 4800000, 110, 'King', ['Pendingin ruangan', 'Wi-Fi cepat', 'Kolam renang pribadi', 'Bathtub outdoor', 'Sarapan harian'], ['V1', 'V2', 'V3', 'V4'], 0],
            ['Family Villa', 'Vila dua kamar dengan dapur kecil dan kolam, cocok untuk keluarga atau rombongan kecil.', 5, 6200000, 180, '2 King', ['Pendingin ruangan', 'Wi-Fi cepat', 'Kolam renang pribadi', 'Dapur kecil', 'Sarapan harian'], ['F1', 'F2', 'F3'], 0],
        ];
        foreach ($types as [$name, $desc, $cap, $price, $size, $bed, $am, $rooms, $floor]) {
            $t = RoomType::firstOrCreate(['slug' => \Illuminate\Support\Str::slug($name)], [
                'name' => $name, 'description' => $desc, 'capacity' => $cap, 'base_price' => $price,
                'size_m2' => $size, 'bed_type' => $bed, 'is_active' => true,
            ]);
            $t->amenities()->sync(collect($am)->map(fn ($n) => $amen[$n]->id));
            foreach ($rooms as $no) {
                Room::firstOrCreate(['room_number' => $no], ['room_type_id' => $t->id, 'floor' => $floor, 'status' => 'available']);
            }
        }

        // Contoh transaksi supaya dashboard & laporan tidak kosong.
        if (\App\Models\Reservation::count() === 0) {
            $svc = app(ReservationService::class);
            $samples = [
                ['deluxe-garden', 'Made Ayu Pratiwi', 'made@example.com', 2, 3, 2, 'pay_at_hotel', null],
                ['ocean-view-suite', 'Daniel Hartono', 'daniel@example.com', 7, 2, 3, 'simulation', 'success'],
                ['private-pool-villa', 'Sarah Whitfield', 'sarah@example.com', 10, 4, 2, 'simulation', 'success'],
            ];
            foreach ($samples as [$slug, $name, $email, $offset, $nights, $g, $method, $outcome]) {
                $in = now()->addDays($offset);
                $r = $svc->create([
                    'room_type_id' => RoomType::where('slug', $slug)->value('id'),
                    'check_in' => $in->toDateString(), 'check_out' => $in->copy()->addDays($nights)->toDateString(),
                    'guests_count' => $g, 'payment_method' => $method,
                    'guest' => ['full_name' => $name, 'email' => $email, 'phone' => '+62 812 0000 0000'],
                ]);
                if ($outcome === 'success') {
                    $svc->changePaymentStatus($r->payment, 'paid');
                }
            }
        }
    }
}
