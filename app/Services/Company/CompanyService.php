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
            $company = $this->get($id);

            if (isset($data['shop_logo']) && $data['shop_logo'] != null) {
                $data['shop_logo'] = $this->fileUploadService->uploadFile($data['shop_logo'],Company::FILE_LOGO_PATH,
                Company::FILE_LOGO_PATH."/".$company->shop_logo
                );
            }
            if (isset($data['shop_image']) && $data['shop_image'] != null) {
                $data['shop_image'] = $this->fileUploadService->uploadFile($data['shop_image'],Company::FILE_IMAGE_PATH,
                Company::FILE_IMAGE_PATH."/".$company->shop_image
            );
            }
            if (isset($data['cover_image']) && $data['cover_image'] != null) {
                $data['cover_image'] = $this->fileUploadService->uploadFile($data['cover_image'],Company::FILE_COVER_IMAGE_PATH, Company::FILE_COVER_IMAGE_PATH."/".$company->cover_image);
            }

            return $company = $this->model::updateOrCreate(
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
