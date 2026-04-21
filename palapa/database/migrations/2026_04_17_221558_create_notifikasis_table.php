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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->increments('notifikasi_id');
            $table->unsignedInteger('user_id'); 
            $table->string('pesan', 255);
            $table->integer('is_read')->nullable()->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('users_id')->on('users')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
