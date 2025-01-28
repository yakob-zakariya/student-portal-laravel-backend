<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'username' => $this->username,

            'role' => $this->when($this->roles->count() > 0, function () {
                return $this->roles->first()->name;
            }),

            'department' => $this->when($this->isCoordinator(), function () {
                return new DepartmentResource($this->coordinator->department);
            }),
        ];
    }

    private function isCoordinator(): bool
    {
        return $this->roles->contains('name', 'coordinator');
    }
}
