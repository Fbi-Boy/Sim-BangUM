<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create("supply_categories", function (Blueprint $t) {
            $t->id(); $t->string("code",40)->unique(); $t->string("name",140);
            $t->boolean("is_active")->default(true); $t->timestamps();
        });
        Schema::create("supply_items", function (Blueprint $t) {
            $t->id(); $t->string("sku",60)->unique();
            $t->foreignId("supply_category_id")->constrained()->restrictOnDelete();
            $t->string("name",180); $t->string("unit",30);
            $t->decimal("minimum_stock",18,3)->default(0);
            $t->decimal("current_stock",18,3)->default(0);
            $t->boolean("is_active")->default(true); $t->timestamps();
        });
        Schema::create("stock_requests", function (Blueprint $t) {
            $t->id(); $t->string("request_number",50)->unique();
            $t->foreignId("requester_user_id")->constrained("users")->restrictOnDelete();
            $t->text("purpose"); $t->dateTime("needed_at")->nullable();
            $t->string("status",25)->default("submitted");
            $t->foreignId("approved_by")->nullable()->constrained("users")->nullOnDelete();
            $t->dateTime("approved_at")->nullable(); $t->text("rejection_reason")->nullable();
            $t->timestamps();
        });
        Schema::create("stock_request_items", function (Blueprint $t) {
            $t->id();
            $t->foreignId("stock_request_id")->constrained()->cascadeOnDelete();
            $t->foreignId("supply_item_id")->constrained()->restrictOnDelete();
            $t->decimal("requested_quantity",18,3);
            $t->decimal("approved_quantity",18,3)->nullable();
            $t->decimal("issued_quantity",18,3)->default(0);
            $t->timestamps();
            $t->unique(["stock_request_id","supply_item_id"]);
        });
    }
    public function down(): void {
        Schema::dropIfExists("stock_request_items");
        Schema::dropIfExists("stock_requests");
        Schema::dropIfExists("supply_items");
        Schema::dropIfExists("supply_categories");
    }
};
