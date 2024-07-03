<?php

namespace App\Http\Resources;

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
        return [
            'id'                => $this->id,
            'class'             => new ClassResource($this->class),
            'feedback'          => $this->feedback,
            'rating'            => $this->rating,
            'created_at'        => (string) $this->created_at,
            'created_at_text'   => Carbon::parse($this->created_at)->format('M d, Y'),
            'updated_at'        => (string) $this->created_at,
            'updated_at_text'   => Carbon::parse($this->updated_at)->format('M d, Y')
        ];
    }
}
