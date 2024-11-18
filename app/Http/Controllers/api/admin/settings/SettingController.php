<?php

namespace App\Http\Controllers\api\admin\settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Services\Utils\FileUploadService;

class SettingController extends Controller
{
    public $fileUploadService;

    public function __construct()
    {
        $this->fileUploadService = new FileUploadService();
    }

    public function getAllSystemSetting(){
        $settingImageFields = ['site_logo_path', 'shop_default_image_path', 'blog_cover_image_path','shop_cover_image_path','banner_image_path'];
        $defaultImageUrl = url('images/default.png');
        $systemSettings = getAllSettingValue();
        if($systemSettings){
            $systemSettings = $systemSettings->pluck('setting_value','setting_key')->toArray();
            foreach($settingImageFields as $field){
                if (array_key_exists($field,$systemSettings))
                {   $oldFilePath = $systemSettings[$field];
                    if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                        $systemSettings[$field] = Storage::disk('public')->url($systemSettings[$field]);
                    }else{
                        $systemSettings[$field] = $defaultImageUrl;
                    }
                }
                else{
                    $systemSettings[$field] = $defaultImageUrl;
                }
            }
        }
        // Optional: Return response or a success message
        return response()->json($systemSettings, 200);

    }
    public function setSystemSetting(Request $request){

         // Define the allowed setting fields
        $allowedFields = [
            'site_name', 'site_address', 'site_phone',
            'site_email', 'site_description',
            'facebook_url', 'instagram_url', 'twitter_url'
        ];

        $settingFields = $request->only($allowedFields);

         // Handle site logo upload
        if ($request->hasFile('site_logo')) {
            $settingFields['site_logo_path'] = $this->fileUploadService->handleFileUpload(
                $request->file('site_logo'),
                'settings',
                'site_logo_path'
            );
        }
        // Handle shop default image upload
        if ($request->hasFile('shop_default_image')) {
            $settingFields['shop_default_image_path'] = $this->fileUploadService->handleFileUpload(
                $request->file('shop_default_image'),
                'settings',
                'shop_default_image_path'
            );
        }
        // Handle blog cover image upload
        if ($request->hasFile('blog_cover_image')) {
            $settingFields['blog_cover_image_path'] = $this->fileUploadService->handleFileUpload(
                $request->file('blog_cover_image'),
                'settings',
                'blog_cover_image_path'
            );
        }
        // Handle shop cover image upload
        if ($request->hasFile('shop_cover_image')) {
            $settingFields['shop_cover_image_path'] = $this->fileUploadService->handleFileUpload(
                $request->file('shop_cover_image'),
                'settings',
                'shop_cover_image_path'
            );
        }
        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            $settingFields['banner_image_path'] = $this->fileUploadService->handleFileUpload(
                $request->file('banner_image'),
                'settings',
                'banner_image_path'
            );
        }

         // Update settings with the new values
        setSettingsKeyValue($settingFields);

        // Optional: Return response or a success message
        return response()->json(['status' => true,'message' => 'Settings updated successfully.'], 200);
    }
}
