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
        Schema::create('rider_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('service_name');
            $table->string('lat');
            $table->string('long');
            $table->timestamp('capture_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rider_locations');
    }
};
