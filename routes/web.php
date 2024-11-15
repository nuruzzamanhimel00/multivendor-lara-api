<?php

use App\Models\login;

use App\Models\Setting;
use App\Models\TestModel;
use App\Models\UserPeople;
use Illuminate\Http\Request;
use Rawilk\Settings\Settings;
use App\Enums\CompanyFeaturedEnum;
use Illuminate\Support\Facades\Route;
use App\Services\Utils\FileUploadService;
use Illuminate\Database\Query\JoinClause;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * @throws \Throwable
 */

Route::get('/', function () {
    //site settings
    $settingData = [
        "site_name"=> "sds",
        "site_address"=> "asda",
        "site_phone"=> "21312",
        "site_email"=> "sad@sfd.com",
        "site_description"=> "dfd",
        "facebook_url"=> "dfd",
        "instagram_url"=> "dfd",
        "twitter_url"=> "dfd",
    ];
    // Create a new setting
        Settings::set('foo', 'bar');

        // Update an existing setting
        Settings::set('foo', 'updated value');
    dd('dd');
    // $enum_data = CompanyFeaturedEnum::;
    // $cases = CompanyFeaturedEnum::cases();
    // dd(enumCasesToSting($cases));
    // $cases = [];
    // foreach (CompanyFeaturedEnum::cases() as $case) {
    //     $cases[strtolower($case->name)] = $case->value;
    // }

    // if(count($cases) > 0){
    //     // Convert the array into the desired string format
    //     $casesString = implode(', ', array_map(
    //         fn($key, $value) => "$key = $value",
    //         array_keys($cases),
    //         $cases
    //     ));
    // }

    // return view('welcome');
});
Route::get('/mediaLibrary', function () {

    return view('mediaLibrary');
});
Route::post('/mediaLibrary', function (Request $request) {
    $yourModel = TestModel::create([
        'name' => rand(111111, 999999)."_name",
    ]);
    $yourModel
   ->addMedia($request->image[0])
   ->toMediaCollection('medial-collection');

    // $fileUploadService  = app(FileUploadService::class);
    // $fileUploadService->uploadToMediaLibrary($yourModel, $request->image, 'medial-collection');

    return redirect()->back();
})->name('mediaLibrary.store');

Route::get('/mediaLibrary/{id}/get', function () {

    //****** TESTING 1 */
    $yourModel = TestModel::first();

    $mediaItems = $yourModel->getMedia('medial-collection');
    $publicUrl = $mediaItems[0]->getUrl();
    $publicFullUrl = $mediaItems[0]->getFullUrl(); // URL including domain
    $fullPathOnDisk = $mediaItems[0]->getPath();

    $getFirstMedia = $yourModel->getFirstMedia('medial-collection');
    $getFirstMediaUrl = $yourModel->getFirstMediaUrl('medial-collection');

    //get conversion image
    $urlToFirstListImagePreview = $yourModel->getFirstMediaUrl('medial-collection', 'preview');
    $urlToFirstListImagePA = $yourModel->getFirstMediaUrl('medial-collection', 'preview_again');

    dd($publicUrl, $publicFullUrl, $fullPathOnDisk, $getFirstMedia, $getFirstMediaUrl, $urlToFirstListImagePreview, $urlToFirstListImagePA);
});


Route::get('/last-three-recoreds', function () {
    // //general code (Not working find)
    // return $userPeople = UserPeople::query()
    // // ->with(['login'=> function($query){
    // //     return $query->latest()->take(2);
    // // }])
    // ->with(['login'])
    // ->get();



    // // //*************joinLateral code
    $latestLogin = login::query()->whereColumn('user_people_id', 'user_people.id')
    ->latest('logins.login_at')
    ->take(2);

    return $userPeople = UserPeople::query()
    ->select(['user_people.name','user_people.id','latest_login.login_at','latest_login.user_people_id'])
    ->joinLateral($latestLogin, 'latest_login')
    ->get();

    // //same query in different way
    // return UserPeople::query()
    // ->with(['login'=>function($query){
    //     return $query->whereIn('id', function($subQuery){
    //         return $subQuery->select('id')->from('logins as ld')
    //         ->whereColumn('ld.user_people_id', 'logins.user_people_id')
    //         ->orderBy('ld.login_at', 'desc')
    //             ->take(2);
    //     });
    // }])
    // ->get();



});
