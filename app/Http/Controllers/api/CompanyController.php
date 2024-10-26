<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter_data = json_decode($request->filters);
        // return json_decode($request->filters)[0];
        // foreach($request->filters as $key => $filter){
        //     return $filter;
        // }

        $p_data = Company::join('users', 'companies.user_id', '=', 'users.id')
        ->join('user_plans', 'companies.user_plan_id', '=', 'user_plans.id')
        ->join('plans', 'companies.plan_id', '=', 'plans.id')
        ->whereHas('user', function($query){
            return $query->isOwner();
        })
        ->with(['user','currentPlan'])
        ->when(isset($request->sortField) && !is_null($request->sortField), function($query) use($request){
            $query->orderBy(strtolower($request->sortField), $request->sortOrder == 1 ? 'asc':'desc');
        })
        ->when( is_null($request->sortField), function($query) use($request){
            $query->latest('companies.id');
        })


        ->select('companies.*','users.*','user_plans.*','user_plans.status as user_plan_status','users.status as user_status','users.id as user_id','companies.id as company_id','plans.name as current_plan_name','users.name')

        ->when(isset($request->search) && !is_null($request->search), function($query) use($request,$filter_data){
            foreach($filter_data as $key => $filter){

                if($key == 0){
                    $query->where($filter, 'like', '%'.$request->search.'%');
                }else{
                    $query->orWhere($filter, 'like', '%'.$request->search.'%');
                }
            }
        })
        ->paginate($request->rows);

        // $all_data = Company::join('users', 'companies.user_id', '=', 'users.id')
        // ->join('user_plans', 'companies.user_plan_id', '=', 'user_plans.id')
        // ->whereHas('user', function($query){
        //     return $query->isOwner();
        // })
        // ->with(['user','currentPlan'])

        // ->select('companies.*','users.*','user_plans.*','user_plans.status as user_plan_status','users.status as user_status','users.id as user_id')
        // ->latest()->get();

        return response()->json([
            'p_data' => $p_data,
            // 'all_data' => $all_data,
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
        $company = Company::with(['user'])->find($id);
        $company->user->delete();
        $company->delete();
        if(request()->ajax()){

            return response()->json(true);
        }
    }

    public function selectedCompanyDelete(Request $request){
        $validatedData = $request->validate([
            'company_ids' => [
                'required',
                'array',
                function($attribute, $value, $fail) {
                    if (!is_array($value)) {
                        return $fail('The '.$attribute.' must be an array.');
                    }
                }
            ],
            'company_ids.*' => [
                'required',
                'integer',
                Rule::exists('companies', 'id')
            ],
        ]);

        foreach($request->company_ids as $company_id){
            $this->destroy($company_id);
        }
        return response()->json(true);
    }
}
