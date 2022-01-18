<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Comment as CommentResource;
use App\Http\Resources\v1\CommentCollection;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unSeenComments = Comment::where('seen', 0)->get();
        foreach ($unSeenComments as $unSeenComment) {
            $unSeenComment->seen = 1;
            $result = $unSeenComment->save();
        }
        $comments = Comment::all();
        return new CommentCollection($comments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valid data
        $validation = $this->validate($request, [
            'body' => 'required|max:600|min:5|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,><\/;\n\r& ]+$/u',
            'parent_id' => 'required|min:1|regex:/^[0-9]+$/u|exists:comments,id',
            'author_id' => 'required|min:1|regex:/^[0-9]+$/u|exists:users,id',
            'post_id' => 'required|min:1|regex:/^[0-9]+$/u|exists:posts,id',
            'status' => 'required|numeric|in:0,1',
            'approved' => 'required|numeric|in:0,1',
        ]);
        $inputs = $request->all();
        $comment = Comment::create($inputs);
        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        // Valid data
        $validation = $this->validate($request, [
            'body' => 'required|max:600|min:5|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,><\/;\n\r& ]+$/u',
            'parent_id' => 'required|min:1|regex:/^[0-9]+$/u|exists:comments,id',
            'author_id' => 'required|min:1|regex:/^[0-9]+$/u|exists:users,id',
            'post_id' => 'required|min:1|regex:/^[0-9]+$/u|exists:posts,id',
            'status' => 'required|numeric|in:0,1',
            'approved' => 'required|numeric|in:0,1',
        ]);
        $inputs = $request->all();
        $comment->update($inputs);
        // return response()->json($comment->user);
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }

    public function status(Comment $comment)
    {
        $comment->status = $comment->status == 0 ? 1 : 0;
        $result = $comment->save();
        if ($result) {
            if ($comment->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }
    public function approved(Comment $comment)
    {
        $comment->approved = $comment->approved == 0 ? 1 : 0;
        $result = $comment->save();
        if ($result) {
            if ($comment->approved == 0) {
                return response()->json(['approved' => true, 'checked' => false]);
            } else {
                return response()->json(['approved' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['approved' => false]);
        }
    }

    public function answer(Request $request, Comment $comment)
    {
        // Valid data
        $validation = $this->validate($request, [
            'body' => 'required|max:600|min:5|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,><\/;\n\r& ]+$/u',
        ]);
        if ($comment->parent == null) {
            $inputs = $request->all();
            $inputs['author_id'] = 1;
            $inputs['parent_id'] = $comment->id;
            $inputs['post_id'] = $comment->post->id;
            $inputs['approved'] = 1;
            $inputs['status'] = 1;
            Comment::create($inputs);
            return new CommentResource($comment);
        } else {
            return response([
                'data' => 'شما نمیتوانید به نظر ادمین پاسخ بدهید',
                'status' => 'error',
            ], 403);
        }
    }
}
