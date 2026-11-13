<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create("documents", function (Blueprint $t) {
            $t->id();
            $t->string("title", 200);
            $t->string("documentable_type", 120);
            $t->unsignedBigInteger("documentable_id");
            $t->string("disk", 40)->default("local");
            $t->string("path", 500);
            $t->string("original_filename", 255);
            $t->string("mime_type", 120);
            $t->unsignedBigInteger("file_size_bytes");
            $t->foreignId("uploaded_by_user_id")->constrained("users")->restrictOnDelete();
            $t->timestamps();
            $t->index(["documentable_type", "documentable_id"]);
        });
    }
    public function down(): void { Schema::dropIfExists("documents"); }
};
