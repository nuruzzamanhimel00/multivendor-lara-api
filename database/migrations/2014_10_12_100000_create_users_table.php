<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('user_type')->default(User::USER_TYPE_SELLER);
            $table->datetime('last_login_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('status')->default(User::STATUS_ACTIVE);
            // Define foreign key constraint with cascade delete
            $table->unsignedBigInteger('plan_id')->nullable(); // Make the column nullable
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');

            $table->unsignedBigInteger('user_plan_id')->nullable(); // Make the column nullable
            $table->foreign('user_plan_id')->references('id')->on('plans')->onDelete('cascade');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
