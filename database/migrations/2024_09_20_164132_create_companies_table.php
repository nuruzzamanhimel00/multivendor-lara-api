<?php

use App\Enums\CompanyDisplayEnum;
use App\Enums\CompanyFeaturedEnum;
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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            // Define foreign key constraint with cascade delete
            $table->unsignedBigInteger('user_id')->nullable(); // Make the column nullable
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('subdomain')->nullable()->unique();
            $table->string('shop_name')->unique();
            $table->text('shop_description')->nullable();
            $table->string('shop_phone')->nullable()->unique();
            $table->string('shop_address')->nullable();
            $table->string('shop_logo')->nullable();
            $table->string('shop_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            // $table->boolean('is_featured')->default(1)->comment('1=Yes, 2=No');
            // $table->boolean('display_product')->default(1)->comment('1=Yes, 2=No');

            $table->boolean('is_featured')->default(CompanyFeaturedEnum::No->value)->comment(enumCasesToSting(CompanyFeaturedEnum::cases()));
            $table->boolean('display_product')->default(CompanyDisplayEnum::Yes->value)->comment(enumCasesToSting(CompanyDisplayEnum::cases()));

            $table->integer('views')->default(0);
            $table->text('payment_info')->nullable();

            // Define foreign key constraint with cascade delete
            $table->unsignedBigInteger('plan_id')->nullable(); // Make the column nullable
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');

            $table->unsignedBigInteger('user_plan_id')->nullable(); // Make the column nullable
            $table->foreign('user_plan_id')->references('id')->on('user_plans')->onDelete('cascade');
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
