<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create("letter_disposition_updates", function (Blueprint $t) {
            $t->id();
            $t->foreignId("letter_disposition_id")->constrained()->cascadeOnDelete();
            $t->foreignId("updated_by_user_id")->constrained("users")->restrictOnDelete();
            $t->string("status", 25);
            $t->text("progress_note")->nullable();
            $t->timestamps();
            $t->index("letter_disposition_id");
        });
    }
    public function down(): void { Schema::dropIfExists("letter_disposition_updates"); }
};
