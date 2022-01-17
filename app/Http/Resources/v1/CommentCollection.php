<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class CommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return [
                    'id' => $item->id,
                    'body' => Str::limit($item->body, 10),
                    'author' => $item->user->name,
                    'post' => $item->post->title,
                    'parent' => $item->parent_id ? Str::limit($item->parent->body, 10) : '',
                ];
            }),
            'status' => 'success',
        ];
    }
}
