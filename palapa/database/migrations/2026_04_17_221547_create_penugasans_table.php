<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('penugasan', function (Blueprint $table) {
            $table->increments('penugasan_id');
            $table->unsignedInteger('report_id');
            $table->unsignedInteger('petugas_id');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('bukti_photo', 255)->nullable();

            $table->foreign('report_id')->references('report_id')->on('reports')->onDelete('cascade');
            $table->foreign('petugas_id')->references('users_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasans');
    }
};
