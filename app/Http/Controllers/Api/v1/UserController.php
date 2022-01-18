<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\User as UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
     /**
     * Login the specified user with name, email and password.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Login the specified user with email and password.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Change the specified user password.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
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

     /**
     * Logout the specified user.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        if (auth()->user()) {
            $user = auth()->user();
            $user->api_token = null;
            $user->save();
            return response([
                'data' => 'شما با موفیقت از حساب خود خارج شدید',
                'status' => 'success',
            ]);
        }
        return response([
            'data' => 'شما به حساب خود وارد نشده اید',
            'status' => 'error',
        ], 401);
    }

}
