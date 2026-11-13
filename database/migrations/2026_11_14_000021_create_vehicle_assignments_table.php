<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create("vehicle_assignments", function (Blueprint $t) {
            $t->id();
            $t->foreignId("vehicle_request_id")->constrained()->cascadeOnDelete();
            $t->foreignId("vehicle_id")->constrained()->restrictOnDelete();
            $t->foreignId("driver_user_id")->constrained("users")->restrictOnDelete();
            $t->foreignId("assigned_by_user_id")->constrained("users")->restrictOnDelete();
            $t->dateTime("planned_start_at");
            $t->dateTime("planned_end_at");
            $t->dateTime("actual_start_at")->nullable();
            $t->dateTime("actual_end_at")->nullable();
            $t->decimal("odometer_start", 12, 1)->nullable();
            $t->decimal("odometer_end", 12, 1)->nullable();
            $t->text("return_notes")->nullable();
            $t->string("status", 20)->default("assigned");
            $t->timestamps();
            $t->index(["driver_user_id","status"], "va_driver_idx");
            $t->index(["vehicle_id","planned_start_at","planned_end_at"], "va_vehicle_idx");
        });
        Schema::create("vehicle_usage_logs", function (Blueprint $t) {
            $t->id();
            $t->foreignId("vehicle_assignment_id")->constrained()->cascadeOnDelete();
            $t->foreignId("recorded_by_user_id")->constrained("users")->restrictOnDelete();
            $t->string("event_type", 30);
            $t->dateTime("happened_at");
            $t->decimal("odometer", 12, 1)->nullable();
            $t->text("note")->nullable();
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("vehicle_usage_logs");
        Schema::dropIfExists("vehicle_assignments");
    }
};
