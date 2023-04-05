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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('lastname')->nullable();
            $table->string('middlename')->nullable();
            
            $table->boolean('isSuperAdmin')->default(false);
            $table->string('role')->nullable(); //team role
            $table->string('profile_picture')->nullable();
            $table->string('password');

            $table->string('notification_preferences')->nullable(); //['new_chats','new_visitors','system_prompts','customer_login']

            $table->unsignedBigInteger('subscription_plan_id')->nullable();
            
            $table->string('completion_rate')->nullable(); //personal details(30%), sub plans(40%), workspace(30%)
            $table->string('status')->default('pending'); ////active, pending, deactivated, etc
            $table->softDeletes();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

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
};
