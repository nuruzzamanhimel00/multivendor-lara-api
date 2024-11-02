<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use App\Models\Category;
use App\Models\UserPlan;
use Illuminate\Database\Seeder;
use Database\Seeders\PlanSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@app.com'],
            [
                'name' => 'Test Admin',
                'email' => 'admin@app.com',
                'password' => Hash::make('12345678'),
                'user_type' => User::USER_TYPE_ADMIN
            ]);
        $owner = User::query()->updateOrCreate(
            ['email' => 'owner@app.com'],
            [
                'name' => 'Test owner',
                'email' => 'owner@app.com',
                'password' => Hash::make('12345678'),
                'user_type' => User::USER_TYPE_SELLER
            ]);


            User::query()->updateOrCreate(
                ['email' => 'user@app.com'],
                [
                    'name' => 'Test User',
                    'email' => 'user@app.com',
                    'password' => Hash::make('12345678'),
                    'user_type' => User::USER_TYPE_USER
                ]);


                $this->call([
                    UserPeopleSeeder::class,
                    // LoginSeeder::class,
                    PlanSeeder::class,
                ]);
                //owner company create
                $CompanyData = [
                    'user_id' => $owner->id,
                    'subdomain' => makeAlias('MyShop'),
                    'shop_name' => 'MyShop',
                    'shop_description' => 'MyShop',
                    'shop_phone' => $owner->phone,
                    'shop_address' => 'MyShop',
                    'shop_logo' => 'shop_logo.png',
                    'shop_image' => 'shop_image.png',
                    'cover_image' => 'cover_image.png',
                    'lat' => '',
                    'lng'=> '',
                    // 'is_featured'=>2,
                    // 'display_product'=>1,
                    'views'=>0,
                    'payment_info'=>'',
                ];
                $company = Company::updateOrCreate(
                    ['user_id' => $owner->id],
                    $CompanyData);
                //owner default category create
                $CategoryData = [
                    'company_id' => $company->id,
                    'category_name' => 'Default',
                    'order_index' => 0,
                    'status' => 'active',
                ];
                $Category = Category::updateOrCreate(
                    ['company_id' => $company->id],
                    $CategoryData
                );
                //owner plan set
                 // Create user plan
                $data = [
                    'user_id' => $owner->id,
                    'plan_id' => 2,
                    'company_id' => $company->id,
                    'document' => '',
                    'status' => UserPlan::ACCEPTED,
                    'status_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addDays(30),
                    'price' => 0
                ];
                $user_plan = UserPlan::updateOrCreate($data);
                //company update
                $CompanyData = [
                    'plan_id' => 2,
                    'user_plan_id' => $user_plan->id
                ];
                $company = Company::updateOrCreate(
                    ['user_id' => $owner->id],
                    $CompanyData);
    }
}
