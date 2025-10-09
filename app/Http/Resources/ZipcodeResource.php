<?php

namespace App\Http\Resources;

use App\Models\ZipcodeUnique;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ZipcodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
		 * @var $this ZipcodeUnique
		 */
		return [
			'id'	=> $this->id,
			'zipcode'	=> $this->zipcode,
		];
    }
}
