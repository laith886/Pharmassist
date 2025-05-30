<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pharmacists', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username');
            $table->string('password');
            $table->string('phone');
            $table->dateTime('employment_date');
            $table->float('salary', 8, 2);
            $table->boolean('is_admin');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pharmacists');
    }
};
