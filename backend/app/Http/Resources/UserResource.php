<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'profile' => [
                'id' => $this->profile->id ?? null,
                'name' => $this->profile->name ?? null,
            ],
            'addresses' => $this->addresses->map(function($a){
                return [
                    'id' => $a->id,
                    'street' => $a->street,
                    'number' => $a->number,
                    'complement'   => $a->complement,
                    'neighborhood' => $a->neighborhood,
                    'city' => $a->city,
                    'state' => $a->state,
                    'zip' => $a->zip,
                    'country' => $a->country,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
