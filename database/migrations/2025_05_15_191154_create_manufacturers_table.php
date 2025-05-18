<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('location');
            $table->string('phone');
            $table->string('email');
            $table->string('website');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('manufacturers');
    }
};
