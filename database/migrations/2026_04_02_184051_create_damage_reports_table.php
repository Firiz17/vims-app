<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->string('severity');
            $table->string('photo')->nullable();
            $table->date('reported_date');

            // Foreign Keys
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('damage_reports');
    }
};
