<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("incoming_letters", function (Blueprint $t) {
            $t->id();
            $t->smallInteger("register_year")->unsigned();
            $t->integer("agenda_sequence")->unsigned();
            $t->string("external_letter_number", 120)->nullable();
            $t->date("letter_date")->nullable();
            $t->dateTime("received_at");
            $t->string("sender_name", 180);
            $t->string("sender_organization", 180)->nullable();
            $t->string("subject", 250);
            $t->text("summary")->nullable();
            $t->string("confidentiality", 25)->default("internal");
            $t->string("urgency", 20)->default("normal");
            $t->string("received_channel", 30)->default("physical");
            $t->foreignId("recipient_unit_id")->nullable()->constrained("organizational_units")->nullOnDelete();
            $t->foreignId("registered_by_user_id")->constrained("users")->restrictOnDelete();
            $t->string("status", 30)->default("registered");
            $t->timestamps();
            $t->unique(["register_year", "agenda_sequence"]);
            $t->index(["status", "received_at"]);
        });
    }
    public function down(): void { Schema::dropIfExists("incoming_letters"); }
};
