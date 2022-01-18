<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
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
                'title' => $this->title,
                'description' => $this->description,
                'image' => url($this->image),
                'author' => $this->user->name,
                'category' => $this->category->name,
                'published_at' => jalaliDate($this->published_at),
                'comment' => new CommentCollection($this->comments),
            ],
            'status' => 'success',
        ];
    }
}
