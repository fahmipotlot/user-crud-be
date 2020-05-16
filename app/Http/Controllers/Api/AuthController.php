<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Auth;
use DB;

use App\User;

class AuthController extends Controller
{
    public function login()
    {
        $this->validate(request(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        
        if(filter_var(request()->email, FILTER_VALIDATE_EMAIL)) {
            $user = User::whereRaw('LOWER(email) = ?', strtolower(request()->email))->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Email / Password doesn\'t match our record'
                ], 401);
            }

            $authenticated = Auth::attempt([
                'email' => $user->email,
                'password' => request()->password
            ]);

            if ($authenticated) {
                $user = Auth::user();
                $user->api_token = str_random(100);
                $user->save();

                $user->token = $user->api_token;

                return $user;
            } else {
                return response()->json([
                    'message' => 'Email / Password doesn\'t match our record'
                ], 401);
            }
        } else {
            $user = User::whereRaw('LOWER(username) = ?', strtolower(request()->email))->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Username / Password doesn\'t match our record'
                ], 401);
            }

            if (password_verify(request()->password, $user->password)) {
                Auth::attempt([
                    'email' => $user->email,
                    'password' => request()->password
                ]);

                $user->api_token = str_random(100);
                $user->save();

                $user->token = $user->api_token;

                return $user;
            } else {
                return response()->json([
                    'message' => 'Username / Password doesn\'t match our record'
                ], 401);
            }
        }
    }

    public function register()
    {
        $this->validate(request(), [
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => 'required|confirmed',
            'name' => 'string|required|max:191'
        ]);

        $save = User::create([
            'email' => request()->email,
            'username' => request()->username,
            'password' => Hash::make(request()->password),
            'api_token' => str_random(100),
            'name' => request()->name
        ]);

        if ($save) {
            $save->token = $save->api_token;
            return $save;
        } else {
            return response()->json([
                'message' => 'error'
            ], 500);
        }
    }
}
