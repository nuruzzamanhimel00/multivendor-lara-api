<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Brand;
use App\Models\Company;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;
use App\Services\Utils\FileUploadService;

/**
 * BrandService
 */
class UserService extends BaseService
{
    public $fileUploadService;
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(User $model, FileUploadService $fileUploadService)
    {
        parent::__construct($model);
        $this->fileUploadService = $fileUploadService;
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
            // Create
            if (isset($data['password']) && $data['password']) {
                $data['password'] = Hash::make($data['password']);
            }
            if (isset($data['avatar']) && $data['avatar'] != null) {
                $data['avatar'] = $this->fileUploadService->uploadFile($data['avatar'], User::FILE_STORE_PATH);
            }
            // Store user
            return $this->model::create($data);
        }
    }
}
