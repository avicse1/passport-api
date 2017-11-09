<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\Controller;

class PassportController extends Controller
{
    public $successStatus = 200;
    public function login() {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            return [
                'success' => $success,
            ];
        } else {
            return [
                'error' => 'unauthorized',
            ];
        }
    }
    public function register(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if($validator->fails()) {
            return [
                'error' => $validator->error(),
            ];
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;
        return [
            'success' => $success
        ];
    }
    public function getDetails() {
        $user = Auth::user();
        return [
            'success' => $user,
        ];
    }
}
