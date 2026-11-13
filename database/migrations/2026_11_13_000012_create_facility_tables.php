<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("rooms", function (Blueprint $t) {
            $t->id();
            $t->string("room_code", 50)->unique();
            $t->string("name", 160);
            $t->smallInteger("capacity")->unsigned();
            $t->string("room_type", 30)->default("meeting");
            $t->string("status", 20)->default("active");
            $t->text("usage_notes")->nullable();
            $t->timestamps();
        });
        Schema::create("room_bookings", function (Blueprint $t) {
            $t->id();
            $t->string("booking_number", 50)->unique();
            $t->foreignId("room_id")->constrained()->restrictOnDelete();
            $t->foreignId("requester_user_id")->constrained("users")->restrictOnDelete();
            $t->string("purpose", 250);
            $t->smallInteger("attendee_count")->unsigned();
            $t->dateTime("starts_at");
            $t->dateTime("ends_at");
            $t->string("status", 25)->default("submitted");
            $t->text("rejection_reason")->nullable();
            $t->timestamps();
            $t->index(["room_id", "starts_at", "ends_at", "status"]);
        });
    }
    public function down(): void {
        Schema::dropIfExists("room_bookings");
        Schema::dropIfExists("rooms");
    }
};
