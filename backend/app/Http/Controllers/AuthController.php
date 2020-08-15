<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use App\Traits\RestApi;
use Illuminate\Support\Facades\Auth;
use Config;

class AuthController extends Controller
{
    use RestApi;

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return $this->resultResponse(
                Config::get('restresponsecode.BAD_REQUEST'),
                [],
                $errors,
                'Field Validation Error!'
            );
        }

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $data['token'] = $user->createToken('RentBookApplication')->accessToken;
            $data['user'] = $user;
            return $this->resultResponse(
                Config::get('restresponsecode.SUCCESS'),
                [$data],
                [],
                'You have been loggedin successfully'
            );
        }
        else {
            return $this->resultResponse(
                Config::get('restresponsecode.UNAUTHORIZED'),
                [],
                [],
                'Wrong credentials!'
            );
        }
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'username' => 'required|string:50|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return $this->resultResponse(
                Config::get('restresponsecode.BAD_REQUEST'),
                [],
                $errors,
                'Field Validation Error!'
            );
        }

        $password = $request->password;
        $data = $request->all();
        $user = User::create($data);

        if(!empty($user)) {
            return $this->resultResponse(
                Config::get('restresponsecode.SUCCESS'),
                [$user],
                [],
                'You have successfully registered.'
            );
        }
    }
}
