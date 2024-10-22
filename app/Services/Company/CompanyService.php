<?php

namespace App\Services\Company;

use App\Models\Brand;
use App\Models\Company;
use App\Services\BaseService;

/**
 * BrandService
 */
class CompanyService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(Company $model)
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

            if (isset($data['shop_logo']) && $data['shop_logo'] != null) {
                $data['shop_logo'] = $this->fileUploadService->uploadFile($data['shop_logo'],Company::FILE_LOGO_PATH);
            }
            if (isset($data['shop_image']) && $data['shop_image'] != null) {
                $data['shop_image'] = $this->fileUploadService->uploadFile($data['shop_image'],Company::FILE_IMAGE_PATH);
            }
            if (isset($data['cover_image']) && $data['cover_image'] != null) {
                $data['cover_image'] = $this->fileUploadService->uploadFile($data['cover_image'],Company::FILE_COVER_IMAGE_PATH);
            }
            // Store user
            return $this->model::create($data);
        }
    }
}
