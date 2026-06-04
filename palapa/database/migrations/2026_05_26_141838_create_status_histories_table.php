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
        Schema::create('status_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('report_id');
            $table->string('status_awal')->nullable();
            $table->string('status_baru');
            $table->text('catatan')->nullable();
            $table->string('diubah_oleh');
            $table->timestamp('tanggal_ubah')->useCurrent();
            $table->timestamps();

            $table->foreign('report_id')->references('report_id')->on('reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_histories');
    }
};
