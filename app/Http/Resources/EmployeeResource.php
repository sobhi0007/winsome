<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
     public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'position'  => $this->position,
            'salary'    => $this->salary,
            'hired_at'  => $this->hired_at,
            'status'    => $this->status,
            'deleted_at'=> $this->deleted_at,
            'image_url' => $this->image ? asset('storage/' . $this->image) : null,

        ];
    }
}
