<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('username')->unique(); 
            $table->string('email')->unique();
            $table->string('phone_number')->nullable(); 
            $table->timestamp('email_verified_at')->nullable();
            $table->string('image_url')->nullable();
            $table->string('personal_details')->nullable();
            $table->string('password');
            $table->boolean('is_totp_setup')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
