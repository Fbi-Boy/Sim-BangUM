<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create("archive_classifications", function (Blueprint $t) {
            $t->id();
            $t->string("code", 60)->unique();
            $t->string("name", 180);
            $t->text("description")->nullable();
            $t->smallInteger("retention_active_months")->unsigned()->nullable();
            $t->string("final_disposition", 30)->nullable();
            $t->boolean("is_active")->default(true);
            $t->timestamps();
        });
        Schema::create("archive_records", function (Blueprint $t) {
            $t->id();
            $t->string("archive_code", 80)->unique();
            $t->foreignId("archive_classification_id")->constrained()->restrictOnDelete();
            $t->string("title", 200);
            $t->text("description")->nullable();
            $t->date("record_date")->nullable();
            $t->string("archive_status", 25)->default("active");
            $t->string("physical_location", 180)->nullable();
            $t->foreignId("registered_by_user_id")->constrained("users")->restrictOnDelete();
            $t->timestamps();
            $t->index(["archive_classification_id","archive_status"]);
        });
    }
    public function down(): void {
        Schema::dropIfExists("archive_records");
        Schema::dropIfExists("archive_classifications");
    }
};
