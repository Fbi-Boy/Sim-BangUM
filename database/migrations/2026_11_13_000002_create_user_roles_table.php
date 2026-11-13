<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("user_roles", function (Blueprint $t) {
            $t->id();
            $t->foreignId("user_id")->constrained()->cascadeOnDelete();
            $t->foreignId("role_id")->constrained()->restrictOnDelete();
            $t->unsignedBigInteger("scope_unit_id")->nullable();
            $t->dateTime("valid_from");
            $t->dateTime("valid_until")->nullable();
            $t->foreignId("granted_by")->nullable()->constrained("users")->nullOnDelete();
            $t->string("grant_reason", 255)->nullable();
            $t->dateTime("revoked_at")->nullable();
            $t->foreignId("revoked_by")->nullable()->constrained("users")->nullOnDelete();
            $t->timestamps();
            $t->index(["user_id", "valid_from", "valid_until"]);
        });
    }
    public function down(): void { Schema::dropIfExists("user_roles"); }
};
