<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\Company;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Services\Utils\FileUploadService;

class CompanyController extends Controller
{
    public $fileUploadService;
    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
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



        return response()->json([
            'p_data' => $p_data,
            // 'all_data' => $all_data,
        ],200);
    }
    public function getCompany($id){

        $company = Company::with(['user'])->find($id);
        return response()->json($company);
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

        // $companyRequestData = $request->only([
        //     'shop_name',
        //     'shop_description',
        //     'shop_phone',
        //     'shop_address',
        //     'is_featured',
        //     'display_product',
        //     'payment_info'
        // ]);


        // $company = Company::find($id);
        // $company->update($companyRequestData);
        // $company = Company::find($id);
        // //company logo url
        // if(isset($request->company_logo_url) && count($request->company_logo_url) > 0){
        //     $this->fileUploadService($company, $request->company_logo_url,'company_logo');
        // }
        // return ($request->all());
    }
    //** COmpany Update */
    public function companyUpdate(Request $request, $id){

        // if ($request->has('company_logo_url')) {
        //     // Loop through each file in the array
        //     foreach ($request->file('company_image_url') as $file) {
        //         // Check if the current file exists
        //         if ($file->isValid()) {
        //             // Process the file (e.g., store it)
        //             $filePath = $file->store('uploads', 'public');

        //             // You can do further processing with $filePath if needed
        //         } else {
        //             return response()->json(['message' => 'One or more files are not valid'], 400);
        //         }
        //     }
        // }
        // return 'ok';
        $companyRequestData = $request->only([
            'shop_name',
            'shop_description',
            'shop_phone',
            'shop_address',
            'is_featured',
            'display_product',
            'payment_info'
        ]);
        $companyRequestData['is_featured'] = $companyRequestData['is_featured'] ? 1 : 0;
        $companyRequestData['display_product'] = $companyRequestData['display_product'] ? 1 : 0;
        // return $companyRequestData;

        $company = Company::find($id);
        $company->update($companyRequestData);
        $company = Company::find($id);


        if(isset($request->company_cover_image_url) && count($request->company_cover_image_url) > 0){
            $this->fileUploadService->uploadToMediaLibrary($company, $request->company_cover_image_url,Company::COMPANY_COVER_IMAGE);
        }
        if(isset($request->company_image_url) && count($request->company_image_url) > 0){
            $this->fileUploadService->uploadToMediaLibrary($company, $request->company_image_url,Company::COMPANY_IMAGE);
        }

        if(isset($request->company_logo_url) && count($request->company_logo_url) > 0){
             $this->fileUploadService->uploadToMediaLibrary($company, $request->company_logo_url,Company::COMPANY_LOGO);

        }

        return response()->json([
            'status'=> true,
            'data' => $company
        ]);

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

    public function logoUpdate(Request $request){
        // return "dd";
        return $request->all();
    }
}
