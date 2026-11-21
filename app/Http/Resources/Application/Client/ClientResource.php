<?php

namespace App\Http\Resources\Application\Client;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public static $wrap = 'payload';

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'entreprise' => $this->entreprise,
            'contact' => $this->contact,
            'logo' => $this->logo ?? 'https://www.freelogoservices.com/blog/wp-content/uploads/Beats-Logo.png',
            'ice' => $this->ice,
        ];
    }
}
