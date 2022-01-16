<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\User as UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // Valid data
        $validation = $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|min:6|string',
        ]);

        $user = User::create([
            'name' => $validation['name'],
            'email' => $validation['email'],
            'password' => Hash::make($validation['password']),
            'api_token' => Str::random(100),
        ]);

        return new UserResource($user);
    }

    public function login(Request $request)
    {
        // Valid data
        $validation = $this->validate($request, [
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6',
        ]);
        // check login user
        if (!auth()->attempt($validation)) {
            return response([
                'data' => 'اطلاعات صحیح نیست',
                'status' => 'error',
            ], 403);
        }

        // Make new api token
        auth()->user()->update([
            'api_token' => Str::random(100),
        ]);

        // return user
        return new UserResource(auth()->user());

    }

    public function changePassword(Request $request)
    {
        // Valid data
        $validation = $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
        ]);
        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return response([
                'data' => 'کلمه عبور قبلی شما اشتباه است',
                'status' => 'error',
            ], 403);
        }
        if ($request->old_password == $request->new_password) {
            return response([
                'data' => 'کلمه عبور قبلی با کلمه عبور جدید یکسان است',
                'status' => 'error',
            ], 403);
        }
        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();
        return new UserResource(auth()->user());
    }

}