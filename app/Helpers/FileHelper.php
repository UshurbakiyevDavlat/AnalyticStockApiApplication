<?php

declare(strict_types=1);

namespace App\Helpers;

class FileHelper
{
    /**
     * Form data for file
     *
     * @param string $path
     * @return string
     */
    public static function getFormData(string $path): string
    {
        return base64_encode(file_get_contents($path));
    }
}