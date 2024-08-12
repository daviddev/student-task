<?php

namespace App\Http\Resources;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Session $resource */
        $resource = $this->resource;

        return [
            'id' => $resource->id,
            'student_id' => $resource->student_id,
            'student_full_name' => $resource->student->full_name,
            'date' => $resource->start_time->format('Y-m-d H:i'),
            'type' => $resource->type,
            'duration' => $resource->duration,
            'rate' => $resource->rate,
            'can_rate' => $resource->can_rate,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
        ];
    }
}
