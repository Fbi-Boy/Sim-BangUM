<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create("official_travel_requests", function (Blueprint $t) {
            $t->id();
            $t->string("request_number", 50)->unique();
            $t->foreignId("requester_user_id")->constrained("users")->restrictOnDelete();
            $t->text("purpose");
            $t->string("destination", 255);
            $t->dateTime("departure_at");
            $t->dateTime("return_at");
            $t->string("transport_mode", 30)->nullable();
            $t->string("status", 25)->default("submitted");
            $t->foreignId("approved_by")->nullable()->constrained("users")->nullOnDelete();
            $t->dateTime("approved_at")->nullable();
            $t->text("rejection_reason")->nullable();
            $t->timestamps();
            $t->index(["requester_user_id","departure_at"]);
        });
        Schema::create("official_travel_participants", function (Blueprint $t) {
            $t->id();
            $t->foreignId("official_travel_request_id")->constrained()->cascadeOnDelete();
            $t->string("participant_name", 180);
            $t->string("participant_role", 30)->default("participant");
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("official_travel_participants");
        Schema::dropIfExists("official_travel_requests");
    }
};
