<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'appointment'       => new AppointmentResource($this->whenLoaded('appointment')),
            'review'            => new ReviewResource($this->whenLoaded('review')),
            'class_id'          => $this->class_id,
            'start_time'        => $this->start_time,
            'end_time'          => $this->end_time,
            'duration'          => $this->duration,
            'status'            => $this->status,
            'created_at'        => (string) $this->created_at,
            'created_at_text'   => Carbon::parse($this->created_at)->format('M d, Y'),
            'updated_at'        => (string) $this->created_at,
            'updated_at_text'   => Carbon::parse($this->updated_at)->format('M d, Y')
        ];
    }
}
