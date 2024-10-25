<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $p_data = Company::query()
        // ->whereHas('user', function($query){
        //     return $query->isOwner();
        // })
        // ->with(['user'])
        // ->when(isset($request->sortField) && !is_null($request->sortField), function($query) use($request){
        //     $query->orderBy(strtolower($request->sortField), $request->sortOrder == 1 ? 'asc':'desc');
        // })
        // ->when( is_null($request->sortField), function($query) use($request){
        //     $query->orderBy('id','desc');
        // })
        // ->paginate($request->rows);

        $p_data = Company::join('users', 'companies.user_id', '=', 'users.id')
        ->whereHas('user', function($query){
            return $query->isOwner();
        })
        ->with(['user'])
        ->when(isset($request->sortField) && !is_null($request->sortField), function($query) use($request){
            $query->orderBy(strtolower($request->sortField), $request->sortOrder == 1 ? 'asc':'desc');
        })
        ->when( is_null($request->sortField), function($query) use($request){
            $query->latest('companies.id');
        })

        ->select('companies.*','users.*')
        ->paginate($request->rows);

        $all_data = Company::query()
        ->select(['id','shop_name'])
        ->whereHas('user', function($query){
            return $query->isOwner();
        })
        ->latest()->get();
        return response()->json([
            'p_data' => $p_data,
            'all_data' => $all_data,
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
