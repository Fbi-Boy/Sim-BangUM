<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("letter_dispositions", function (Blueprint $t) {
            $t->id();
            $t->foreignId("incoming_letter_id")->constrained()->cascadeOnDelete();
            $t->foreignId("from_user_id")->constrained("users")->restrictOnDelete();
            $t->foreignId("to_user_id")->nullable()->constrained("users")->nullOnDelete();
            $t->foreignId("to_unit_id")->nullable()->constrained("organizational_units")->nullOnDelete();
            $t->text("instruction");
            $t->string("priority", 20)->default("normal");
            $t->dateTime("due_at")->nullable();
            $t->string("status", 25)->default("assigned");
            $t->timestamps();
            $t->index(["incoming_letter_id", "status"]);
            $t->index(["to_user_id", "status"]);
        });
    }
    public function down(): void { Schema::dropIfExists("letter_dispositions"); }
};
