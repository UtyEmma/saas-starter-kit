<?php

namespace App\Http\Resources\Billing;

use App\Http\Resources\Features\FeatureResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'is_popular' => $this->is_popular,
            'is_default' => $this->is_default,
            'is_free' => $this->is_free,
            'trial_period' => $this->trial_period,
            'grace_period' => $this->grace_period,
            'features' => FeatureResource::collection($this->whenLoaded('features')),
            'prices' => PricingResource::collection($this->whenLoaded('prices')),
        ];
    }
}
