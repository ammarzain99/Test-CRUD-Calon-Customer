<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PotentialCustomerResource extends JsonResource
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
            'nama' => $this->nama,
            'no_wa' => $this->no_wa,
            'email' => $this->email,
            'nama_lembaga' => $this->nama_lembaga,
            'created_at' => $this->created_at,
        ];
    }
}
