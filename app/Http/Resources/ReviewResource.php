<?php

namespace App\Http\Resources;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $appointment = Appointment::find($this->class->appointment_id);
        return [
            'id'                => $this->id,
            'class'             => new ClassResource($this->class),
            'student_name'      => $appointment->student->first_name . ' ' . $appointment->student->last_name,
            'feedback'          => $this->feedback,
            'rating'            => $this->rating,
            'created_at'        => (string) $this->created_at,
            'created_at_text'   => Carbon::parse($this->created_at)->format('M d, Y'),
            'updated_at'        => (string) $this->created_at,
            'updated_at_text'   => Carbon::parse($this->updated_at)->format('M d, Y')
        ];
    }
}
