<?php

namespace App\Domain\Team\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
      'name' => $this->name,
      'slug' => $this->slug,
      'color' => $this->color,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at
    ];
  }
}
