<?php

namespace App\Http\Controllers\api\admin\auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use App\Enums\CompanyDisplayEnum;
use App\Enums\CompanyFeaturedEnum;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Plan\UserPlanService;
use App\Jobs\AdminNotifyRegisCreateJob;
use App\Jobs\OwnerNotifyRegisCreateJob;
use App\Services\Company\CompanyService;
use App\Services\Category\CategoryService;
use App\Notifications\Mail\RegisterCreateAdminNotify;

class AuthController extends Controller
{
    public $userService;
    public $companyService;
    public $categoryService;
    public $userPlanService;
    public function __construct(UserService $userService, CompanyService $companyService, CategoryService $categoryService,
    UserPlanService $userPlanService){
        $this->userService = $userService;
        $this->companyService = $companyService;
        $this->categoryService = $categoryService;
        $this->userPlanService = $userPlanService;
    }
    public function register(Request $request){


        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'shop_name' => 'required|string|max:255|unique:companies,shop_name',
            // 'password' => 'min:8|required_with:confirmed_password|same:confirmed_password',
            // 'confirmed_password' => 'min:8'
        ]);
        $password = rand(111111, 999999);

        try {
            DB::beginTransaction();

            //user create
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $password,
                'user_type' => User::USER_TYPE_SELLER,
                'phone' => $request->phone
            ];
            $owner = $this->userService->createOrUpdate($userData);
            $token = $owner->createToken('MyApp')->plainTextToken;

            //owner company create
            $CompanyData = [
                'user_id' => $owner->id,
                'subdomain' => makeAlias($request->shop_name),
                'shop_name' => $request->shop_name,
                'shop_description' => '',
                'shop_phone' => $owner->phone,
                'shop_address' => null,
                'shop_logo' => null,
                'shop_image' => null,
                'cover_image' => null,
                'lat' => '',
                'lng'=> '',
                'is_featured'=>CompanyFeaturedEnum::No->value,
                'display_product'=>CompanyDisplayEnum::Yes->value,
                'views'=>0,
                'payment_info'=>'',
            ];
            $company = $this->companyService->createOrUpdate($CompanyData);
            DB::commit();
            $CategoryData = [
                'company_id' => $company->id,
                'category_name' => 'Default',
                'order_index' => 0,
                'status' => 'active',
            ];
            $category = $this->categoryService->createOrUpdate($CategoryData);
            DB::commit();
            $UserPlanData = [
                'user_id' => $owner->id,
                'plan_id' => 1,
                'company_id' => $company->id,
                'document' => '',
                'status' => UserPlan::ACCEPTED,
                'status_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(30),
                'price' => 0
            ];
            $user_Plan = $this->userPlanService->createOrUpdate($UserPlanData);
            DB::commit();
            //company update
            $CompanyData = [
                'plan_id' => 1,
                'user_plan_id' => $user_Plan->id
            ];
            // return ($CompanyData);
            $company = $this->companyService->createOrUpdate($CompanyData, $company->id);
            DB::commit();
            // //job work  for mail send to user after new registration create
            AdminNotifyRegisCreateJob::dispatch($owner);
            //owner notify
            OwnerNotifyRegisCreateJob::dispatch($owner,$password);


            return response()->json([
                'status' => true,
                "token" => $token,
                "user" => $owner,
                "message" => 'Register successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            if (config('app.debug')) {
                // Return detailed error information in development
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(), // Optional: Include stack trace for debugging
                ], 500); // Internal Server Error status code
            } else {
                // Return a generic error message in production
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong. Please try again later.',
                ], 500);
            }
        }
    }
    public function login(Request $request)
    {


        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:4'
        ]);
        try {
            $user = User::where('email', $request->email)
            ->isActive()
            ->first();

            if (!$user || !Hash::check($request->password, $user->password) || !in_array($user->user_type, User::ADMIN_USER_TYPES) ) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Credentials'
                ]);
            }

            $token = $user->createToken('MyApp')->plainTextToken;

            return response()->json([
                'status' => true,
                "token" => $token,
                "user" => $user,
                "message" => 'Login successfully',
            ]);

        } catch (\Exception $e) {

            if (config('app.debug')) {
                // Return detailed error information in development
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(), // Optional: Include stack trace for debugging
                ], 500); // Internal Server Error status code
            } else {
                // Return a generic error message in production
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong. Please try again later.',
                ], 500);
            }
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->update([
            'login_user_id' => null
        ]);
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);

    }

    public function impersonateOwner($ownerId)
    {
        // Ensure the admin is authenticated
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        auth()->user()->update([
            'login_user_id' => $ownerId
        ]);

        return response()->json(['message' => 'Successfully impersonated the owner']);
    }
    public function stopImpersonation()
    {
        // Ensure the admin is authenticated
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        auth()->user()->update([
            'login_user_id' => null
        ]);

        return response()->json(['message' => 'Successfully stopped impersonated the owner']);
    }
}
