<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'file' => $this->attachment
                ? Storage::disk('admin')->url($this->attachment)
                : null,
            'created_at' => $this->created_at,
        ];
    }
}
