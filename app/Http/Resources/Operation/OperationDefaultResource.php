<?php

namespace App\Http\Resources\Operation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationDefaultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'amount'=>$this->amount,
            'description'=>$this->description,
            'type_operation'=>$this->type_operation,
            'created_at'=>$this->created_at->diffForHumans(),
            'updated_at'=>$this->updated_at->diffForHumans(),
        ];
    }
}
