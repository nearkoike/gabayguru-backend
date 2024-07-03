<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptResource extends JsonResource
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
            'appointment_id'    => $this->appointment_id,
            'receiver'          => new UserResource($this->receiver),
            'receiver_name'     => $this->receiver_name,
            'sender'            => new UserResource($this->sender),
            'sender_name'       => $this->sender_name,
            'amount'            => $this->amount,
            'service_charge'    => $this->service_charge,
            'created_at'        => (string) $this->created_at,
            'created_at_text'   => Carbon::parse($this->created_at)->format('M d, Y'),
            'updated_at'        => (string) $this->created_at,
            'updated_at_text'   => Carbon::parse($this->updated_at)->format('M d, Y')
        ];
    }
}
