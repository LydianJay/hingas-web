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
        Schema::create('room', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('thumbnail')->nullable();
            $table->json('images')->nullable();
            $table->float('rate', 2);
            $table->boolean('is_active')->default(1);
            
        });



        Schema::create('reservation', function (Blueprint $table) {
            $table->id();
            $table->string('reservee');
            $table->string('address')->nullable();
            $table->string('contactno')->nullable();
            $table->date('date');
            $table->string('r_date')->nullable();
            $table->time('time');
            $table->integer('hours');
            $table->foreignId('room_id')->constrained('room');
        });


        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('res_id')->constrained('reservation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
