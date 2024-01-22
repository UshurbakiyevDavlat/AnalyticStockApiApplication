<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @method total()
 * @method perPage()
 * @method currentPage()
 * @method lastPage()
 */
class PostCollection extends ResourceCollection
{
    public array $data;

    /**
     * @param $resource
     * @param array|null $data
     */
    public function __construct($resource, ?array $data = [])
    {
        $this->data = $data;
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!empty($this->data['paginated'])) {
            $pagination = [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
            ];
        }

        return [
            'data' => $this->collection,
            'pagination' => $pagination ?? null,
        ];
    }
}
