<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create("events", function (Blueprint $t) {
            $t->id();
            $t->string("event_number", 50)->unique();
            $t->string("title", 220);
            $t->string("event_type", 40)->default("lainnya");
            $t->foreignId("coordinator_user_id")->constrained("users")->restrictOnDelete();
            $t->dateTime("starts_at"); $t->dateTime("ends_at");
            $t->string("location", 255)->nullable();
            $t->text("description")->nullable();
            $t->string("status", 25)->default("draft");
            $t->timestamps();
            $t->index(["starts_at","status"]);
        });
        Schema::create("event_agendas", function (Blueprint $t) {
            $t->id();
            $t->foreignId("event_id")->constrained()->cascadeOnDelete();
            $t->smallInteger("sequence_number")->unsigned();
            $t->string("agenda_title", 180);
            $t->dateTime("starts_at"); $t->dateTime("ends_at");
            $t->string("person_in_charge", 180)->nullable();
            $t->text("notes")->nullable();
            $t->timestamps();
            $t->unique(["event_id","sequence_number"]);
        });
        Schema::create("event_tasks", function (Blueprint $t) {
            $t->id();
            $t->foreignId("event_id")->constrained()->cascadeOnDelete();
            $t->string("task_title", 180);
            $t->text("description")->nullable();
            $t->foreignId("assigned_to_user_id")->nullable()->constrained("users")->nullOnDelete();
            $t->dateTime("due_at")->nullable();
            $t->string("priority", 20)->default("normal");
            $t->string("status", 25)->default("open");
            $t->dateTime("completed_at")->nullable();
            $t->timestamps();
            $t->index(["event_id","status"]);
        });
    }
    public function down(): void {
        Schema::dropIfExists("event_tasks");
        Schema::dropIfExists("event_agendas");
        Schema::dropIfExists("events");
    }
};
