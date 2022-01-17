<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class Comment extends JsonResource
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
            'data' => [
                'id' => $this->id,
                'body' => Str::limit($this->body, 10),
                'author' => $this->user->name,
                'post' => $this->post->title,
                'parent' => $this->parent_id ? Str::limit($this->parent->body, 10) : '',
            ],
            'status' => 'success',
        ];
    }
}
