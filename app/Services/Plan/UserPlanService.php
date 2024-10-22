<?php

namespace App\Services\Plan;

use App\Models\Brand;
use App\Models\Company;
use App\Models\Category;
use App\Models\UserPlan;
use App\Services\BaseService;

/**
 * BrandService
 */
class UserPlanService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(UserPlan $model)
    {
        parent::__construct($model);
    }

    public function createOrUpdate(array $data, $id = null)
    {
        if ($id) {
            // Update
            $customer = $this->get($id);

            // Password
            if (isset($data['password']) && $data['password']) {
                $data['password'] = Hash::make($data['password']);
            }

            // // Avatar
            if (isset($data['avatar']) && $data['avatar'] != null) {
                $data['avatar'] = $this->uploadFile($data['avatar'], $customer->avatar);
            }

            if (isset($data['back_avatar']) && $data['back_avatar'] != null) {
                $data['back_avatar'] = $this->uploadFile($data['back_avatar'], $customer->back_avatar);
            }
            // dd('inside $data', $data);
            return $customer = $this->model::updateOrCreate(
                [
                    'id' => $id
                ],
                $data
            );
        } else {

            // Store user
            return $this->model::create($data);
        }
    }
}
