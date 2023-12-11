<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\FileHelper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @throws Exception
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'file_type' => $this->fileType->title,
            'formData' => FileHelper::getFormData($this->path),
            'created_at' => $this->created_at,
        ];
    }
}
