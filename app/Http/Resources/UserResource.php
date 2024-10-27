<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'image'=> asset('storage/'.$this->image),

            'code' => $this->code,
            'group_id' => $this->group_id,
            'fcm_token' => $this->fcm_token,
            'status' => (string)$this->status,
            'registered_at' => $this->registered_at,
            'group'=> new GroupResource($this->group),
            'branch'=> new BranchResource($this->branch),
            'token' => "Bearer ".$this->token,
        ];
    }
}
