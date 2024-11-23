<?php

namespace App\Http\Controllers\api\admin\plan;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Enums\PlanPeriodEnum;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter_data = json_decode($request->filters);

        $p_data = Plan::query()
        ->when(isset($request->sortField) && !is_null($request->sortField), function($query) use($request){
            $query->orderBy(strtolower($request->sortField), $request->sortOrder == 1 ? 'asc':'desc');
        })
        ->when( is_null($request->sortField), function($query) use($request){
            $query->latest('id');
        })

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
        $validated = $request->validate([
            'name' => 'required|unique:plans|max:255',
            'description' => 'required',
            'features' => 'required',
            'price' => 'required',
            'item_limit' => 'required',
            'order_limit' => 'required',
            'period' =>['required', new Enum(PlanPeriodEnum::class)],
        ]);
        $plan = Plan::create($validated);
        return response()->json([
            'status'=> true,
            'data' => $plan
        ]);
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

    public function planEnableOrdering(Request $request){
        $validated = $request->validate([
            'plan_id' => 'required',
            'enable_orders' => 'required',
        ]);
        $plan = Plan::find($request->plan_id);
        $plan->enable_orders = $request->enable_orders;
        $plan->save();
        return response()->json([
            'status'=> true,
            'data' => $plan
        ]);
    }

    public function planPeriodChange(Request $request){
        $validated = $request->validate([
            'plan_id' => 'required',
            'period' => 'required',
        ]);
        $plan = Plan::find($request->plan_id);
        $plan->period = $request->period ? PlanPeriodEnum::MONTHLY : PlanPeriodEnum::ANNUALLY;
        $plan->save();
        return response()->json([
            'status'=> true,
            'data' => $plan
        ]);
    }
}
