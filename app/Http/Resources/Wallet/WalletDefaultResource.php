<?php

namespace App\Http\Resources\Wallet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletDefaultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'balance'=>$this->balance,
            'created_at'=>$this->created_at->diffForHumans(),
            'updated_at'=>$this->updated_at->diffForHumans(),
        ];
    }
}
