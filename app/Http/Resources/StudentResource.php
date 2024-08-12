<?php

namespace App\Http\Resources;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Student $resource */
        $resource = $this->resource;

        return [
            'id' => $resource->id,
            'first_name' => $resource->first_name,
            'middle_name' => $resource->middle_name,
            'last_name' => $resource->last_name,
            'full_name' => $resource->full_name,
            'email' => $resource->email,
            'gender' => $resource->gender,
            'phone_number' => $resource->phone_number,
            'birthdate' => $resource->birthdate->format('Y-m-d'),
            'availability' => $resource->availability,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
        ];
    }
}
