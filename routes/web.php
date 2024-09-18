<?php

use App\Models\login;
use App\Models\UserPeople;
use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return view('welcome');
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
