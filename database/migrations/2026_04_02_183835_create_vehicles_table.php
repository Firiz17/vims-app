<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vin')->unique();
            $table->string('model');
            $table->string('principal');
            $table->string('status')->default('Checked-In');
            $table->date('arrival_date');
            $table->date('delivered_date')->nullable();

            // Foreign Key
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
};
