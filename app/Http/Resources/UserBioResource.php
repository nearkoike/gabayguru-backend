<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserBioResource extends JsonResource
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
            'user'                  => new UserResource($this->whenLoaded('user')),
            'specialization'        => $this->specialization,
            'years_of_experience'   => $this->years_of_experience,
            'professional_bio'      => $this->professional_bio,
            'work_experience'       => $this->work_experience,
            'links'                 => $this->links,
            'resume'                => $this->resume,
            'portfolio'             => $this->portfolio,
        ];
    }
}
