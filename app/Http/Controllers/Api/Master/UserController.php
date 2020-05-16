<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Auth;
use DB;

use App\User;

class UserController extends Controller
{
    public function index()
    {
    	return User::orderBy('id', 'desc')->paginate(25);
    }

    public function store()
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

    public function show($id)
    {
        return User::find($id);
    }

    public function update($id)
    {
        $this->validate(request(), [
            'email' => 'required|email|unique:users,email,'. $id .'',
            'username' => 'required|unique:users,username,'. $id .'',
            'name' => 'string|required|max:191'
        ]);

        $user = User::find($id);
        
        $user->update([
            'email' => request()->email,
            'username' => request()->username,
            'name' => request()->name
        ]);

        if ($user) {
            return $user;
        } else {
            return response()->json([
                'message' => 'error'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        $user->delete();

        return response()->json([
            'message' => 'delete success',
        ], 200);
    }
}
