<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'id'                    => $this->id,
            'first_name'            => $this->first_name,
            'last_name'             => $this->last_name,
            'username'              => $this->username,
            'email'                 => $this->email,
            'contact_number'        => $this->contact_number,
            'date_of_birth'         => $this->date_of_birth,
            'profile_picture'       => $this->profile_picture,
            'role'                  => $this->role,
            'rate'                  => $this->rate,
            'wallet'                => $this->wallet,
            'transactions'          => UserTransactionResource::collection($this->whenLoaded('transactions')),
            'student_appointments'  => AppointmentResource::collection($this->whenLoaded('student_appointments')),
            'mentor_appointments'   => AppointmentResource::collection($this->whenLoaded('mentor_appointments')),
            'created_at'            => (string) $this->created_at,
            'created_at_text'       => Carbon::parse($this->created_at)->format('M d, Y'),
            'updated_at'            => (string) $this->created_at,
            'updated_at_text'       => Carbon::parse($this->updated_at)->format('M d, Y')
        ];
    }
}
