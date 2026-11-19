<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('letter_dispositions', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_disposition_id')->nullable()->after('id');
            $table->foreign('parent_disposition_id')->references('id')->on('letter_dispositions')->nullOnDelete();
            $table->dateTime('submitted_at')->nullable()->after('status');
            $table->dateTime('reviewed_at')->nullable()->after('submitted_at');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('reviewed_at');
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('letter_dispositions', function (Blueprint $table) {
            $table->dropForeign(['parent_disposition_id']);
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['parent_disposition_id', 'submitted_at', 'reviewed_at', 'reviewed_by']);
        });
    }
};
