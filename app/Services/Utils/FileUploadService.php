<?php

namespace App\Services\Utils;

use Exception;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageResizeTrait;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
/**
 * FileUploadService
 */
class FileUploadService
{
    use ImageResizeTrait;

    /**
     * uploadFile
     *
     * @param  mixed $file
     * @param  mixed $upload_path
     * @param  mixed $delete_path
     * @return void
     */
    public function uploadFile($file, $upload_path = null, $delete_path = null, $use_original_name = false)
    {
        try {
            // Upload image
            // Delete old file
            if ($delete_path) {
                $this->delete($delete_path);
            }
            // Upload new file
            return $this->upload($file, $upload_path, $use_original_name);
        } catch (Exception $ex) {
            return null;
        }
    }

    /**
     * upload
     *
     * @param  mixed $file
     * @param  mixed $path
     * @return void
     */
    public function upload($file, $path = 'others', $use_original_name = false)
    {
        try {
            if (!$use_original_name) {
                $name = time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
            } else {
                $full_name = $file->getClientOriginalName();
                $extract_name = explode('.', $full_name);

                $name = generateSlug($extract_name[0]) . '-' . time() . '.' . $file->getClientOriginalExtension();
            }
            // Store image to public disk
            $file->storeAs($path, $name);
            return $name ?? '';
        } catch (Exception $ex) {
            return '';
        }
    }

    /**
     * delete
     *
     * @param  mixed $path
     * @return void
     */
    public function delete($path = '')
    {
        try {
            // Delete image form public directory
            Storage::disk(config('filesystems.default'))->delete($path);

        } catch (Exception $ex) {
        }
    }

    public function uploadBase64File($field_name, $upload_path = null, $img_name = null, $delete_path = null, $width = null, $height = null, $image_resize = null)
    {
        try {
            // Upload image
            if ($field_name) {
                // Delete old file
                if ($delete_path) {
                    $this->delete($delete_path);
                }
                // Upload new file
                return $this->uploadBase64($field_name, $upload_path, $img_name, $width, $height, $image_resize);
            }
            return null;
        } catch (\Exception $ex) {
            return null;
        }
    }

    public function uploadBase64($file, $path = 'others', $name = null, $width = null, $height = null, $image_resize = null)
    {
        try {
            if ($file) {
                if (is_null($name)) {
                    $name = time() . Str::uuid() . '.png';
                }

                $img = substr($file, strpos($file, ",") + 1);

                if ($image_resize) {
                    $this->imageResize($image_resize, $file, $name, $path, $width, $height);
                } else {
                    if (!is_null($width) || !is_null($height)) {
                        $img = Image::make($file);

                        $resize_imag = $img->fit($width, $height);
                        Storage::disk('public')->put($path . '/' . $name, (string) $resize_imag->encode());
                    } else {
                        Storage::disk('public')->put($path . '/' . $name, base64_decode($img));
                    }
                }

                return $name;
            }
        } catch (\Exception $ex) {
            return '';
        }
    }

     /**
     * @throws \Throwable
     */
    public function uploadToMediaLibrary(Model $model, array $files, string $collectionName = 'default', string $fileName = '', string $disk = 'public'): void
    {

        try {
            // Filter and delete media that no longer exists in the $files array
            $model->getMedia($collectionName)
                ->filter(function ($media) use($files) {
                    return !$files || !in_array($media->getUrl(), $files);
                })
                ->each->delete();



            // Get only the new files that are not already in the media collection
            $existingFiles = $model->getMedia($collectionName)
                ->map(fn($media) => $media->getUrl())
                ->toArray();

            $newFiles = array_diff($files, $existingFiles);

            foreach ($newFiles as $file) {
                $media = $model->addMedia($file);
                // $media = $model->addMediaFromDisk($file, $disk);

                if ($fileName) {
                    $media->usingFileName($fileName);
                }

                $media->toMediaCollection($collectionName);
            }
        } catch (\Throwable $throwable) {
            throw $throwable;
        }
    }

}
