<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $currentPrice
 * @property mixed $openPrice
 * @property mixed $targetPrice
 * @property mixed $potential
 * @property mixed $recommend
 * @property mixed $analyzePrice
 * @property mixed $horizon
 * @property mixed $country_id
 * @property mixed $status
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $risk
 * @property mixed $sector_id
 * @property mixed $ticker
 * @property mixed $isin
 */
class HorizonDatasetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "currentPrice" => $this->currentPrice,
            "openPrice" => $this->openPrice,
            "targetPrice" => $this->targetPrice,
            "potential" => $this->potential,
            "recommend" => $this->recommend,
            "analyzePrice" => $this->analyzePrice,
            "horizon" => $this->horizon,
            "tickers" => $this->ticker?->map(
                fn($ticker) => [
                    'fullName' => $ticker->full_name,
                    'shortName' => $ticker->short_name,
                ],
            ),
            "isins" => $this->isin?->map(
                fn($isin) => [
                    'code' => $isin->code,
                ],
            ),
            "country_id" => $this->country_id,
            "status" => $this->status,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "risk" => $this->risk,
            "sector_id" => $this->sector_id,
        ];
    }
}
