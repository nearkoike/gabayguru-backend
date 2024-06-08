<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'date'              => $this->date,
            'date_text'         => Carbon::parse($this->date)->format('M d, Y'),
            'message'           => $this->message,
            'mentor'            => new UserResource($this->whenLoaded('mentor')),
            'student'           => new UserResource($this->whenLoaded('student')),
            'amount'            => $this->amount,
            'status'            => $this->status,
            'created_at'        => (string) $this->created_at,
            'created_at_text'   => Carbon::parse($this->created_at)->format('M d, Y'),
            'updated_at'        => (string) $this->created_at,
            'updated_at_text'   => Carbon::parse($this->updated_at)->format('M d, Y')
        ];
    }
}