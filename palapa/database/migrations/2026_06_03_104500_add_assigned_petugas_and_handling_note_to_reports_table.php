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
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedInteger('assigned_petugas_id')->nullable()->after('user_id');
            $table->text('handling_note')->nullable()->after('description');

            $table->foreign('assigned_petugas_id')
                ->references('users_id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['assigned_petugas_id']);
            $table->dropColumn(['assigned_petugas_id', 'handling_note']);
        });
    }
};
