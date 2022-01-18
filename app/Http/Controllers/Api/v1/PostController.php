<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Post as PostResource;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return PostResource::collection($posts);
    }

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valid data
        $validation = $this->validate($request, [
            'title' => 'required|max:120|min:2|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u',
            'description' => 'required|max:600|min:5|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,><\/;\n\r& ]+$/u',
            'category_id' => 'required|min:1|regex:/^[0-9]+$/u|exists:categories,id',
            'author_id' => 'required|min:1|regex:/^[0-9]+$/u|exists:users,id',
            'image' => 'required|mimes:jpg,png,jpeg|max:1024',
            'status' => 'required|numeric|in:0,1',
            'published_at' => 'required|numeric',
        ]);
        $inputs = $request->all();
        // date fixed => remove 000 of last timestamp
        $realTimestampStart = substr($request->published_at, 0, 10);
        $inputs['published_at'] = date("Y-m-d H:i:s", (int) $realTimestampStart);

        // get image
        $file = $request->file('image');
        $imagePath = 'upload/images/post';
        $fileName = $file->getClientOriginalName();
        if (file_exists(public_path("{$imagePath}/{$fileName}"))) {
            $fileName = Carbon::now()->timestamp . "-{$fileName}";
        }
        $file->move(public_path($imagePath), $fileName);
        $inputs['image'] = "{$imagePath}/{$fileName}";

        // create post
        $post = Post::create($inputs);

        return new PostResource($post);

    }

    /**
     * Display the specified post.
     *
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Show the form for editing the specified post.
     *
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // Valid data
        $validation = $this->validate($request, [
            'title' => 'required|max:120|min:2|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u',
            'description' => 'required|max:600|min:5|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,><\/;\n\r& ]+$/u',
            'category_id' => 'required|min:1|regex:/^[0-9]+$/u|exists:categories,id',
            'author_id' => 'required|min:1|regex:/^[0-9]+$/u|exists:users,id',
            'image' => 'required',
            'status' => 'required|numeric|in:0,1',
            'published_at' => 'required|numeric',
        ]);
        $inputs = $request->all();
        // date fixed => remove 000 of last timestamp
        $realTimestampStart = substr($request->published_at, 0, 10);
        $inputs['published_at'] = date("Y-m-d H:i:s", (int) $realTimestampStart);

        // create post
        $post->update($inputs);

        return new PostResource($post);
    }

    /**
     * Remove the specified post from storage.
     *
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response(null, '204');
    }
    /**
     * Change the specified post status.
     *
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    public function status(Post $post)
    {
        $post->status = $post->status == 0 ? 1 : 0;
        $result = $post->save();
        if ($result) {
            if ($post->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }
}
