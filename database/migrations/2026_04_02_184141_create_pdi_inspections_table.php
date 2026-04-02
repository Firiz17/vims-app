<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pdi_inspections', function (Blueprint $table) {
            $table->id();
            $table->boolean('tyre')->default(false);
            $table->boolean('oil_level')->default(false);
            $table->boolean('coolant')->default(false);
            $table->boolean('interior_cleanliness')->default(false);
            $table->boolean('tinted_window')->default(false);
            $table->boolean('dashcam')->default(false);
            $table->date('inspection_date');
            $table->string('result');

            // Foreign Keys
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pdi_inspections');
    }
};
