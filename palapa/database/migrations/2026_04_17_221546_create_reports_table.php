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
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('report_id');
            $table->unsignedInteger('admin_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('title', 255);
            $table->string('description', 255)->nullable();
            $table->string('photo', 255)->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('status', 255);

            $table->foreign('admin_id')->references('users_id')->on('users')->onDelete('set null');
            $table->foreign('user_id')->references('users_id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
