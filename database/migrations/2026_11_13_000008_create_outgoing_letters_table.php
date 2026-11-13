<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("outgoing_letters", function (Blueprint $t) {
            $t->id();
            $t->smallInteger("register_year")->unsigned();
            $t->integer("agenda_sequence")->unsigned();
            $t->string("official_letter_number", 120)->nullable();
            $t->date("letter_date")->nullable();
            $t->string("subject", 250);
            $t->text("body_summary")->nullable();
            $t->foreignId("creating_unit_id")->nullable()->constrained("organizational_units")->nullOnDelete();
            $t->foreignId("created_by_user_id")->constrained("users")->restrictOnDelete();
            $t->dateTime("sent_at")->nullable();
            $t->string("status", 30)->default("draft");
            $t->timestamps();
            $t->unique(["register_year", "agenda_sequence"]);
        });
    }
    public function down(): void { Schema::dropIfExists("outgoing_letters"); }
};
