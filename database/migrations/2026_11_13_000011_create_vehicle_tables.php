<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("vehicles", function (Blueprint $t) {
            $t->id();
            $t->string("license_plate", 20)->unique();
            $t->string("make", 80)->nullable();
            $t->string("model", 100)->nullable();
            $t->smallInteger("manufacture_year")->unsigned()->nullable();
            $t->smallInteger("passenger_capacity")->unsigned()->nullable();
            $t->string("status", 25)->default("available");
            $t->timestamps();
        });
        Schema::create("vehicle_requests", function (Blueprint $t) {
            $t->id();
            $t->string("request_number", 50)->unique();
            $t->foreignId("requester_user_id")->constrained("users")->restrictOnDelete();
            $t->foreignId("vehicle_id")->nullable()->constrained()->nullOnDelete();
            $t->text("purpose");
            $t->string("destination", 255);
            $t->dateTime("starts_at");
            $t->dateTime("ends_at");
            $t->smallInteger("passenger_count")->unsigned()->default(1);
            $t->string("status", 25)->default("submitted");
            $t->text("rejection_reason")->nullable();
            $t->timestamps();
            $t->index(["vehicle_id", "status"]);
            $t->index(["starts_at", "ends_at"]);
        });
    }
    public function down(): void {
        Schema::dropIfExists("vehicle_requests");
        Schema::dropIfExists("vehicles");
    }
};
