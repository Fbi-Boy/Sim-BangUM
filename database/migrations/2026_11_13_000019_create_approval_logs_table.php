<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create("approval_logs", function (Blueprint $t) {
            $t->id();
            $t->string("subject_type", 120);
            $t->unsignedBigInteger("subject_id");
            $t->foreignId("approver_user_id")->constrained("users")->restrictOnDelete();
            $t->string("decision", 20);
            $t->text("note")->nullable();
            $t->timestamps();
            $t->index(["subject_type", "subject_id"]);
        });
    }
    public function down(): void { Schema::dropIfExists("approval_logs"); }
};
