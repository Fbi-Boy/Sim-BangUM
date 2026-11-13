<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create("bmd_assets", function (Blueprint $t) {
            $t->id();
            $t->string("bmd_code", 80)->unique();
            $t->foreignId("asset_id")->nullable()->constrained()->nullOnDelete();
            $t->string("name", 200);
            $t->string("category", 100)->nullable();
            $t->smallInteger("year_acquired")->unsigned()->nullable();
            $t->decimal("acquisition_value", 18, 2)->nullable();
            $t->string("condition", 25)->default("good");
            $t->string("bmd_status", 25)->default("active");
            $t->text("notes")->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists("bmd_assets"); }
};
