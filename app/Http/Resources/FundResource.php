<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'id' => $this->id,
            'name' => $this->name,
            'wkn' => $this->wkn,
            'isin' => $this->isin,
            'category' => new FundCategoryResource($this->whenLoaded('category')),
            'subCategory' => new FundSubCategoryResource($this->whenLoaded('subCategory')),
       ];
    }
}
