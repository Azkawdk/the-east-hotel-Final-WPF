<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $t) {
            $t->id();
            $t->string('full_name', 120);
            $t->string('email', 150)->unique();
            $t->string('phone', 30);
            $t->string('country', 80)->nullable();
            $t->string('id_number', 50)->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('room_types', function (Blueprint $t) {
            $t->id();
            $t->string('name', 100);
            $t->string('slug', 120)->unique();
            $t->text('description');
            $t->unsignedTinyInteger('capacity');
            $t->decimal('base_price', 12, 2);
            $t->unsignedSmallInteger('size_m2')->nullable();
            $t->string('bed_type', 60)->nullable();
            $t->string('image_url', 255)->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('rooms', function (Blueprint $t) {
            $t->id();
            $t->foreignId('room_type_id')->constrained('room_types')->restrictOnDelete();
            $t->string('room_number', 10)->unique();
            $t->unsignedTinyInteger('floor')->default(1);
            $t->enum('status', ['available', 'maintenance', 'out_of_service'])->default('available');
            $t->string('notes', 255)->nullable();
            $t->timestamps();
            $t->softDeletes();
            $t->index(['room_type_id', 'status']);
        });

        Schema::create('amenities', function (Blueprint $t) {
            $t->id();
            $t->string('name', 100);
            $t->string('description', 255)->nullable();
            $t->enum('scope', ['hotel', 'room'])->default('room');
            $t->timestamps();
        });

        Schema::create('amenity_room_type', function (Blueprint $t) {
            $t->foreignId('amenity_id')->constrained('amenities')->cascadeOnDelete();
            $t->foreignId('room_type_id')->constrained('room_types')->cascadeOnDelete();
            $t->primary(['amenity_id', 'room_type_id']);
        });

        Schema::create('reservations', function (Blueprint $t) {
            $t->id();
            $t->string('code', 12)->unique();
            $t->foreignId('guest_id')->constrained('guests')->restrictOnDelete();
            $t->date('check_in');
            $t->date('check_out');
            $t->unsignedTinyInteger('guests_count');
            $t->unsignedSmallInteger('nights');
            $t->decimal('subtotal', 12, 2);
            $t->decimal('service_amount', 12, 2)->default(0);
            $t->decimal('tax_amount', 12, 2)->default(0);
            $t->decimal('total_amount', 12, 2);
            $t->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])->default('pending');
            $t->string('special_requests', 500)->nullable();
            $t->timestamp('expires_at')->nullable();
            $t->timestamp('cancelled_at')->nullable();
            $t->string('cancel_reason', 255)->nullable();
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index(['check_in', 'check_out']);
            $t->index('status');
        });

        Schema::create('reservation_details', function (Blueprint $t) {
            $t->id();
            $t->foreignId('reservation_id')->constrained('reservations')->restrictOnDelete();
            $t->foreignId('room_id')->constrained('rooms')->restrictOnDelete();
            $t->foreignId('room_type_id')->constrained('room_types')->restrictOnDelete();
            $t->decimal('price_per_night', 12, 2); // snapshot harga saat pemesanan
            $t->unsignedSmallInteger('nights');
            $t->decimal('subtotal', 12, 2);
            $t->timestamps();
            $t->index('room_id');
        });

        Schema::create('payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('reservation_id')->constrained('reservations')->restrictOnDelete();
            $t->decimal('amount', 12, 2);
            $t->enum('method', ['simulation', 'pay_at_hotel', 'cash', 'bank_transfer'])->default('simulation');
            $t->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $t->string('reference', 40)->nullable()->unique();
            $t->timestamp('paid_at')->nullable();
            $t->string('notes', 255)->nullable();
            $t->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index('status');
        });
    }

    public function down(): void
    {
        foreach (['payments', 'reservation_details', 'reservations', 'amenity_room_type', 'amenities', 'rooms', 'room_types', 'guests'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
