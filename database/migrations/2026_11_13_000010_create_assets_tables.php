<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("asset_categories", function (Blueprint $t) {
            $t->id();
            $t->string("code", 40)->unique();
            $t->string("name", 140);
            $t->boolean("is_active")->default(true);
            $t->timestamps();
        });
        Schema::create("assets", function (Blueprint $t) {
            $t->id();
            $t->string("asset_code", 60)->unique();
            $t->foreignId("asset_category_id")->constrained()->restrictOnDelete();
            $t->string("name", 180);
            $t->string("brand", 100)->nullable();
            $t->string("condition", 25)->default("good");
            $t->string("status", 25)->default("available");
            $t->string("qr_code_value", 100)->nullable()->unique();
            $t->text("notes")->nullable();
            $t->foreignId("created_by_user_id")->constrained("users")->restrictOnDelete();
            $t->timestamps();
            $t->index(["status", "asset_category_id"]);
        });
        Schema::create("asset_loans", function (Blueprint $t) {
            $t->id();
            $t->string("loan_number", 50)->unique();
            $t->foreignId("asset_id")->constrained()->restrictOnDelete();
            $t->foreignId("requester_user_id")->constrained("users")->restrictOnDelete();
            $t->dateTime("starts_at");
            $t->dateTime("expected_return_at");
            $t->text("purpose");
            $t->string("status", 25)->default("submitted");
            $t->dateTime("returned_at")->nullable();
            $t->timestamps();
            $t->index(["asset_id", "status"]);
        });
    }
    public function down(): void {
        Schema::dropIfExists("asset_loans");
        Schema::dropIfExists("assets");
        Schema::dropIfExists("asset_categories");
    }
};
