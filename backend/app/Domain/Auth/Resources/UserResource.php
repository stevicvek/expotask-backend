<?php

namespace App\Domain\Auth\Resources;

use App\Domain\Team\Resources\UsersTeamResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'fullname' => $this->fullname,
      'email' => $this->email,
      'teams' => UsersTeamResource::collection($this->whenLoaded('teams')),
    ];
  }
}
