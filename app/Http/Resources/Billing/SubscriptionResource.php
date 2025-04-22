<?php

namespace App\Http\Resources\Billing;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'plan' => new PlanResource($this->whenLoaded('plan')),
            'price' => new PricingResource($this->whenLoaded('price')),
            'transaction' => new TransactionResource($this->whenLoaded('transaction')),
            'start_date' => $this->starts_at->format('jS M, Y'),
            'reference' => $this->reference,
            'end_date' => $this->expires_at->format('jS M, Y'),
            'auto_renews' => $this->auto_renews,
            'status' => $this->status->name,
            'status_label' => $this->status->label(),
            'status_color' => $this->status->color(),
            'grace_end_date' => $this->grace_ends_at ? $this->grace_ends_at->format('jS M, Y') : null, 
            'trial_end_date' => $this->trial_ends_at ? $this->trial_ends_at->format('jS M, Y') : null,
        ];
    }
}
