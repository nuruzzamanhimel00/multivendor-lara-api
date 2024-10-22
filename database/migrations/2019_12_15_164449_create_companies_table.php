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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            // Define foreign key constraint with cascade delete
            $table->unsignedBigInteger('user_id')->nullable(); // Make the column nullable
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('subdomain')->nullable();
            $table->string('shop_name');
            $table->text('shop_description')->nullable();
            $table->string('shop_phone')->nullable();
            $table->string('shop_address')->nullable();
            $table->string('shop_logo')->nullable();
            $table->string('shop_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->boolean('is_featured')->default(1)->comment('1=Yes, 2=No');
            $table->boolean('display_product')->default(1)->comment('1=Yes, 2=No');
            $table->integer('views')->default(0);
            $table->text('payment_info')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
