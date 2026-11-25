<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Amenity;
use App\Models\RoomType;
use App\Models\Room;
use Illuminate\Support\Str;

echo "Memulai input data 10 tipe kamar hotel luxury...\n";

// 1. Data Amenities (Fasilitas)
$amenitiesList = [
    ['Pendingin ruangan (AC)', 'room', 'AC inverter sunyi dengan kontrol suhu presisi'],
    ['Wi-Fi berkecepatan tinggi', 'room', 'Koneksi nirkabel fiber optic berkecepatan tinggi'],
    ['Balkon / Teras pribadi', 'room', 'Area santai terbuka dengan pemandangan alami'],
    ['Bathtub marmer outdoor', 'room', 'Bak mandi batu alam terbuka dengan air hangat'],
    ['Kolam renang pribadi', 'room', 'Private infinity pool dengan air jernih'],
    ['Sarapan a la carte harian', 'room', 'Pilihan sarapan gourmet lokal & internasional'],
    ['Dapur kecil gourmet', 'room', 'Peralatan masak lengkap, kulkas, dan minibar'],
    ['Layanan Butler 24 Jam', 'room', 'Pramutamu pribadi yang siap melayani 24 jam'],
    ['Antar-Jemput Bandara VIP', 'room', 'Armada mobil mewah dengan pengemudi pribadi'],
    ['Floating Breakfast', 'room', 'Sarapan mengapung romantis di kolam pribadi'],
    ['Kolam renang tebing tanpa batas', 'hotel', 'Infinity pool di tepi tebing menghadap laut lepas'],
    ['The Sacred Frangipani Spa', 'hotel', 'Perawatan holistik & pijat tradisional Bali'],
    ['Samudra Oceanfront Dining', 'hotel', 'Restoran tepi laut dengan hidangan nusantara & seafood'],
];

$amenityMap = [];
foreach ($amenitiesList as [$name, $scope, $desc]) {
    $am = Amenity::firstOrCreate(
        ['name' => $name],
        ['scope' => $scope, 'description' => $desc]
    );
    $amenityMap[$name] = $am->id;
}
echo "✓ Fasilitas berhasil disinkronkan.\n";

// 2. Data 10 Tipe Kamar Luxury
$luxuryRoomTypes = [
    [
        'name' => 'Deluxe Ocean Terrace',
        'description' => 'Kamar lapang dengan teras pribadi menghadap langsung ke birunya Samudra Hindia. Dilengkapi tempat tidur Grand King berbalut katun Mesir halus 400-thread-count, kamar mandi marmer alam dengan bathtub batu, dan pemandangan matahari terbit Karangasem yang memukau.',
        'capacity' => 2,
        'base_price' => 2850000,
        'size_m2' => 52,
        'bed_type' => '1 Grand King Bed',
        'image_url' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=2070&auto=format&fit=crop',
        'amenities' => [
            'Pendingin ruangan (AC)',
            'Wi-Fi berkecepatan tinggi',
            'Balkon / Teras pribadi',
            'Bathtub marmer outdoor',
            'Sarapan a la carte harian',
        ],
        'rooms' => [
            ['room_number' => '101', 'floor' => 1, 'notes' => 'Lantai 1 - Teras langsung taman & laut'],
            ['room_number' => '102', 'floor' => 1, 'notes' => 'Lantai 1 - Posisi tengah tenang'],
            ['room_number' => '103', 'floor' => 1, 'notes' => 'Lantai 1 - Dekat jalan setapak frangipani'],
        ],
    ],
    [
        'name' => 'Premier Tropical Garden Suite',
        'description' => 'Suite tenang yang dikelilingi taman tropis rimbun pohon kamboja dan pakis eksotis. Menawarkan paviliun bale luar ruangan untuk bersantai, ruang tamu luas bernuansa kayu jati daur ulang, dan pancuran hujan (rain shower) semi-terbuka yang menyejukkan.',
        'capacity' => 2,
        'base_price' => 3450000,
        'size_m2' => 68,
        'bed_type' => '1 King Bed',
        'image_url' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2070&auto=format&fit=crop',
        'amenities' => [
            'Pendingin ruangan (AC)',
            'Wi-Fi berkecepatan tinggi',
            'Balkon / Teras pribadi',
            'Sarapan a la carte harian',
        ],
        'rooms' => [
            ['room_number' => '104', 'floor' => 1, 'notes' => 'Lantai 1 - Menghadap taman timur'],
            ['room_number' => '105', 'floor' => 1, 'notes' => 'Lantai 1 - Akses privat ke kebun kamboja'],
        ],
    ],
    [
        'name' => 'Cliffside Ocean Suite',
        'description' => 'Bertengger anggun di atas tebing Karangasem dengan panorama 180 derajat cakrawala Selat Lombok. Menampilkan ruang keluarga terpisah, minibar artisan gratis, serta bathtub tepi tebing dengan kaca lebar untuk menyaksikan deburan ombak laut lepas.',
        'capacity' => 3,
        'base_price' => 4750000,
        'size_m2' => 85,
        'bed_type' => '1 King + 1 Sofa Daybed',
        'image_url' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?q=80&w=2074&auto=format&fit=crop',
        'amenities' => [
            'Pendingin ruangan (AC)',
            'Wi-Fi berkecepatan tinggi',
            'Balkon / Teras pribadi',
            'Bathtub marmer outdoor',
            'Sarapan a la carte harian',
            'Layanan Butler 24 Jam',
        ],
        'rooms' => [
            ['room_number' => '201', 'floor' => 2, 'notes' => 'Lantai 2 - Pemandangan laut tak terhalang'],
            ['room_number' => '202', 'floor' => 2, 'notes' => 'Lantai 2 - Menghadap matahari terbit'],
        ],
    ],
    [
        'name' => 'One-Bedroom Private Pool Villa',
        'description' => 'Vila peristirahatan privat dengan kolam renang tanpa batas pribadi berukuran 8x3 meter, bale bengong beratap alang-alang Bali, dapur kecil gourmet, dan halaman berpagar batu lava hitam yang menjamin privasi dan ketenangan mutlak.',
        'capacity' => 2,
        'base_price' => 6200000,
        'size_m2' => 125,
        'bed_type' => '1 Grand King Bed',
        'image_url' => 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=2070&auto=format&fit=crop',
        'amenities' => [
            'Kolam renang pribadi',
            'Pendingin ruangan (AC)',
            'Wi-Fi berkecepatan tinggi',
            'Bathtub marmer outdoor',
            'Dapur kecil gourmet',
            'Sarapan a la carte harian',
        ],
        'rooms' => [
            ['room_number' => 'V01', 'floor' => 0, 'notes' => 'Vila Privat No. 1 - Kolam renang privat'],
            ['room_number' => 'V02', 'floor' => 0, 'notes' => 'Vila Privat No. 2 - Kolam renang privat'],
            ['room_number' => 'V03', 'floor' => 0, 'notes' => 'Vila Privat No. 3 - Kolam renang privat'],
        ],
    ],
    [
        'name' => 'Two-Bedroom Family Sanctuary Villa',
        'description' => 'Vila dua kamar tidur mewah yang sempurna untuk liburan keluarga atau rombongan sahabat. Dilengkapi dua kamar mandi en-suite berdinding batu alam, kolam renang pribadi luas, ruang keluarga terbuka bergaya arsitektur paviliun Bali, dan meja makan kayu trembesi solid.',
        'capacity' => 4,
        'base_price' => 8900000,
        'size_m2' => 210,
        'bed_type' => '2 Grand King Beds',
        'image_url' => 'https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=2070&auto=format&fit=crop',
        'amenities' => [
            'Kolam renang pribadi',
            'Pendingin ruangan (AC)',
            'Wi-Fi berkecepatan tinggi',
            'Dapur kecil gourmet',
            'Bathtub marmer outdoor',
            'Sarapan a la carte harian',
        ],
        'rooms' => [
            ['room_number' => 'V04', 'floor' => 0, 'notes' => 'Family Villa 2 Kamar - Kolam luas'],
            ['room_number' => 'V05', 'floor' => 0, 'notes' => 'Family Villa 2 Kamar - Dekat pantai'],
        ],
    ],
    [
        'name' => 'The Sunset Honeymoon Pool Villa',
        'description' => 'Diciptakan secara intim untuk pasangan berbulan madu. Menghadap ke ufuk barat tempat matahari terbenam memancarkan rona keemasan. Menikmati kolam renang pribadi dengan taburan kelopak bunga kamboja, floating breakfast di kolam, dan bak mandi aromaterapi herbal.',
        'capacity' => 2,
        'base_price' => 7500000,
        'size_m2' => 140,
        'bed_type' => '1 Romantic Canopy King Bed',
        'image_url' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=2080&auto=format&fit=crop',
        'amenities' => [
            'Kolam renang pribadi',
            'Bathtub marmer outdoor',
            'Pendingin ruangan (AC)',
            'Wi-Fi berkecepatan tinggi',
            'Floating Breakfast',
            'Sarapan a la carte harian',
        ],
        'rooms' => [
            ['room_number' => 'H01', 'floor' => 0, 'notes' => 'Honeymoon Villa 1 - Sunset View'],
            ['room_number' => 'H02', 'floor' => 0, 'notes' => 'Honeymoon Villa 2 - Sunset View'],
        ],
    ],
    [
        'name' => 'Oceanfront Plunge Pool Suite',
        'description' => 'Hanya beberapa langkah dari pasir pantai Karangasem. Dilengkapi kolam renang rendam (plunge pool) pribadi dengan jet hidroterapi, dek jemur kayu ulin dengan sun lounger empuk, dan suara deburan ombak laut yang menenangkan tidur Anda.',
        'capacity' => 3,
        'base_price' => 5600000,
        'size_m2' => 95,
        'bed_type' => '1 King Bed + 1 Sofa Bed',
        'image_url' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=2070&auto=format&fit=crop',
        'amenities' => [
            'Kolam renang pribadi',
            'Pendingin ruangan (AC)',
            'Wi-Fi berkecepatan tinggi',
            'Balkon / Teras pribadi',
            'Bathtub marmer outdoor',
            'Sarapan a la carte harian',
        ],
        'rooms' => [
            ['room_number' => '203', 'floor' => 2, 'notes' => 'Lantai 2 - Menghadap garis pantai'],
            ['room_number' => '204', 'floor' => 2, 'notes' => 'Lantai 2 - Sudut pantai tenang'],
        ],
    ],
    [
        'name' => 'Agung Mountain View Villa',
        'description' => 'Menghadap ke arah siluet sakral Gunung Agung dan lembah persawahan terasering yang hijau. Menawarkan kesejukan angin perbukitan, kolam renang pribadi air jernih, serta paviliun khusus untuk yoga dan meditasi di pagi hari.',
        'capacity' => 3,
        'base_price' => 6850000,
        'size_m2' => 160,
        'bed_type' => '1 Grand King Bed',
        'image_url' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=2070&auto=format&fit=crop',
        'amenities' => [
            'Kolam renang pribadi',
            'Pendingin ruangan (AC)',
            'Wi-Fi berkecepatan tinggi',
            'Dapur kecil gourmet',
            'Balkon / Teras pribadi',
            'Sarapan a la carte harian',
        ],
        'rooms' => [
            ['room_number' => 'V06', 'floor' => 0, 'notes' => 'Vila View Gunung Agung - Kolam pribadi'],
            ['room_number' => 'V07', 'floor' => 0, 'notes' => 'Vila View Gunung Agung - Paviliun yoga'],
        ],
    ],
    [
        'name' => 'Three-Bedroom Royal Heritage Villa',
        'description' => 'Perpaduan agung warisan arsitektur puri Bali dengan kemewahan kelas dunia. Memiliki 3 paviliun tidur terpisah yang mengelilingi kolam renang utama 15 meter, bale perjamuan makan malam, dan layanan butler eksklusif 24 jam.',
        'capacity' => 6,
        'base_price' => 14500000,
        'size_m2' => 380,
        'bed_type' => '3 Grand King Beds',
        'image_url' => 'https://images.unsplash.com/photo-1602002418082-a4443e081dd1?q=80&w=2070&auto=format&fit=crop',
        'amenities' => [
            'Kolam renang pribadi',
            'Pendingin ruangan (AC)',
            'Wi-Fi berkecepatan tinggi',
            'Dapur kecil gourmet',
            'Bathtub marmer outdoor',
            'Sarapan a la carte harian',
            'Layanan Butler 24 Jam',
            'Antar-Jemput Bandara VIP',
        ],
        'rooms' => [
            ['room_number' => 'RH1', 'floor' => 0, 'notes' => 'Royal Heritage Villa Utama'],
        ],
    ],
    [
        'name' => 'The Presidential Cliff Residence',
        'description' => 'Puncak kemewahan tertinggi The East Hotel. Resor megah empat kamar tidur di tanjung tebing pribadi dengan kolam renang infinity dua tingkat, ruang bioskop privat, ruang spa pribadi, dan panorama samudra tak berujung tanpa halangan apa pun.',
        'capacity' => 8,
        'base_price' => 28000000,
        'size_m2' => 650,
        'bed_type' => '4 Super King Beds',
        'image_url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=2070&auto=format&fit=crop',
        'amenities' => [
            'Kolam renang pribadi',
            'Pendingin ruangan (AC)',
            'Wi-Fi berkecepatan tinggi',
            'Dapur kecil gourmet',
            'Bathtub marmer outdoor',
            'Sarapan a la carte harian',
            'Layanan Butler 24 Jam',
            'Antar-Jemput Bandara VIP',
            'Floating Breakfast',
        ],
        'rooms' => [
            ['room_number' => 'PCR1', 'floor' => 0, 'notes' => 'Presidential Cliff Residence - Tanjung Tebing'],
        ],
    ],
];

foreach ($luxuryRoomTypes as $data) {
    $slug = Str::slug($data['name']);
    
    // Periksa apakah tipe kamar sudah ada atau ter-soft-delete
    $roomType = RoomType::withTrashed()->where('slug', $slug)->first();
    
    if (!$roomType) {
        $roomType = new RoomType();
    } else {
        // Pulihkan jika soft-deleted
        $roomType->restore();
    }

    $roomType->name = $data['name'];
    $roomType->slug = $slug;
    $roomType->description = $data['description'];
    $roomType->capacity = $data['capacity'];
    $roomType->base_price = $data['base_price'];
    $roomType->size_m2 = $data['size_m2'];
    $roomType->bed_type = $data['bed_type'];
    $roomType->image_url = $data['image_url'];
    $roomType->is_active = true;
    $roomType->deleted_at = null;
    $roomType->save();

    // Kaitkan relasi fasilitas
    $amenityIds = [];
    foreach ($data['amenities'] as $amName) {
        if (isset($amenityMap[$amName])) {
            $amenityIds[] = $amenityMap[$amName];
        }
    }
    $roomType->amenities()->sync($amenityIds);

    // Buat unit kamar fisik (rooms) jika belum ada
    foreach ($data['rooms'] as $rmData) {
        $room = Room::withTrashed()->where('room_number', $rmData['room_number'])->first();
        if (!$room) {
            $room = new Room();
        } else {
            $room->restore();
        }
        $room->room_type_id = $roomType->id;
        $room->room_number = $rmData['room_number'];
        $room->floor = $rmData['floor'];
        $room->status = 'available';
        $room->notes = $rmData['notes'];
        $room->deleted_at = null;
        $room->save();
    }

    echo "✓ [ID: {$roomType->id}] {$roomType->name} (Rp " . number_format($roomType->base_price, 0, ',', '.') . ") - {$roomType->rooms()->count()} kamar aktif.\n";
}

echo "\nSukses! 10 Tipe Kamar Luxury dan unit kamar fisiknya telah berhasil disimpan ke database.\n";
