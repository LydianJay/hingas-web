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
            $table->string('photo')->nullable()->default(null);
            
        }); 


        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('CASCADE');
            $table->string('role_name');

        });


        

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('module_name');
            $table->string('icon');
        });

        Schema::create('sub_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('CASCADE');
            $table->string('subm_name');
            $table->string('route');
        });

        Schema::create('role_subm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subm_id')->constrained('sub_modules')->onDelete('CASCADE');
            $table->foreignId('role_id')->constrained('role')->onDelete('CASCADE');
        });

        Schema::create('dance', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('session_count')->default(1);
            $table->float('price')->default(1.0);
        });


        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('password');
            $table->foreignId('user_id')->constrained('users')->onDelete('CASCADE');
            $table->foreignId('role_id')->constrained('role');
        }); 


        Schema::create('enrollment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('CASCADE');
            $table->foreignId('dance_id')->constrained('dance')->onDelete('CASCADE');
            $table->boolean('is_active')->default(1);
        });

        Schema::create('dance_session', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollment')->onDelete('CASCADE');
            $table->time('time_in');
            $table->time('time_out')->nullable();
            $table->date('date');
        });

       
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });


        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admin');
            $table->foreignId('user_id')->constrained('users');
            $table->float('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        
        Schema::dropIfExists('sessions');
    }
};
