<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('parent_message_id')->nullable()->constrained('messages')->onDelete('cascade');
            $table->boolean('allow_reply')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['parent_message_id']);
            $table->dropColumn('parent_message_id');
            $table->dropColumn('allow_reply');
        });
    }
};
