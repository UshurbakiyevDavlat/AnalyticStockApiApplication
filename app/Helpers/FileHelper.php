<?php

declare(strict_types=1);

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    /**
     * Form data for file
     *
     * @throws Exception
     */
    public static function getFormData(string $path): string
    {
        $url = Storage::disk('admin-docker')->url($path);

        $context = stream_context_create([
            'http' => [
                'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            ],
        ]);

        // Use file_get_contents with the created context to fetch the file content
        $fileContent = file_get_contents($url, false, $context);

        if ($fileContent === false) {
            // Handle the error (e.g., file not found, permission issues, etc.)
            throw new Exception("Failed to fetch file from $url");
        }

        return base64_encode($fileContent);
    }
}
