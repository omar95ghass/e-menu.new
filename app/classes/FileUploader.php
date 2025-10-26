<?php

namespace App\Classes;

use Exception;
use function config;

class FileUploader
{
    private string $baseDir;

    public function __construct(string $baseDir)
    {
        $this->baseDir = rtrim($baseDir, '/');
    }

    public function upload(array $file, int $restaurantId): array
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload failed');
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes, true)) {
            throw new Exception('Invalid file type');
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $name = uniqid('img_', true) . '.' . $ext;
        $restaurantDir = $this->baseDir . '/' . $restaurantId;
        if (!is_dir($restaurantDir)) {
            mkdir($restaurantDir, 0755, true);
        }
        $target = $restaurantDir . '/' . $name;
        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new Exception('Failed to move uploaded file');
        }

        $thumbnail = $restaurantDir . '/thumb_' . $name;
        $this->createThumbnail($target, $thumbnail);

        return [
            'path' => $target,
            'thumbnail' => $thumbnail,
        ];
    }

    private function createThumbnail(string $source, string $destination): void
    {
        $info = getimagesize($source);
        [$width, $height] = $info;
        $thumbWidth = config('app.constants.THUMBNAIL_WIDTH');
        $thumbHeight = config('app.constants.THUMBNAIL_HEIGHT');
        $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);

        switch ($info['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($source);
                break;
            default:
                throw new Exception('Unsupported image type');
        }

        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
        imagejpeg($thumb, $destination, 85);

        imagedestroy($thumb);
        imagedestroy($image);
    }
}
