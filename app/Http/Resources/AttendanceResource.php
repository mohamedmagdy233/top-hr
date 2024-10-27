<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => asset($this->image),

            'check_in' => $this->check_in = $this->check_in
                ? date('H:i', strtotime($this->check_in))

                : '00:00',
            'check_out' => $this->check_out = $this->check_out
                ? date('H:i', strtotime($this->check_out))

                : '00:00',
            'diff_time' => $this->diff_time,
            'lat' => $this->lat,
            'long' => $this->long,
            'date' => $this->date,
            'check_out_lat' => $this->check_out_lat,
            'check_out_long' => $this->check_out_long,
            'check_out_image' => $this->check_out_image != null ? asset('storage' . $this->check_out_image) : null,
            'user' => new UserResource($this->user),
        ];
    }
}
