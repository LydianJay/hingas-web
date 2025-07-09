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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('rfid')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('fname');
            $table->string('mname')->nullable()->default(null);
            $table->string('lname');
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->date('dob');
            $table->string('contactno')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->boolean('is_active')->default(true);
            $table->string('e_contact')->nullable()->default(null);
            $table->string('e_contact_no')->nullable()->default(null);
            $table->timestamp('email_verified_at')->nullable()->default(null);
            $table->string('photo')->nullable()->default(null);
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->rememberToken()->nullable()->default(null); 
            $table->timestamps();
        });


        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('CASCADE');
        });

        Schema::create('dance', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('enrollment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('CASCADE');
            $table->foreignId('dance_id')->constrained('dance')->onDelete('CASCADE');
        });

        Schema::create('dance_session', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollment')->onDelete('CASCADE');
            $table->time('time_in');
            $table->time('time_out')->nullable();
            $table->date('date');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
