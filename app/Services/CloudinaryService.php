<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'dft73rrav'),
                'api_key'    => env('CLOUDINARY_API_KEY', '685855527125819'),
                'api_secret' => env('CLOUDINARY_API_SECRET', '73CbvUvB6RPVQglCHc6LgoNLBjs'),
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }

    /**
     * Upload a file to Cloudinary.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return array [secure_url, public_id]
     */
    public function upload(UploadedFile $file, string $folder = 'submissions'): array
    {
        $result = $this->cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder' => $folder,
                'resource_type' => 'auto', // Detect PDF, DOCX, Images, etc. automatically
            ]
        );

        return [
            'secure_url' => $result['secure_url'],
            'public_id'  => $result['public_id'],
        ];
    }

    /**
     * Delete an existing file from Cloudinary.
     *
     * @param string|null $publicId
     * @return bool
     */
    public function delete(?string $publicId): bool
    {
        if (empty($publicId)) {
            return false;
        }

        try {
            $this->cloudinary->uploadApi()->destroy($publicId);
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to delete Cloudinary file with public ID '{$publicId}': " . $e->getMessage());
            return false;
        }
    }
}
