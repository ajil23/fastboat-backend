<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthControllerApi extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Input invalid',
                'data' => $validator->errors()
            ]);
        }
        
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json([
            'success' => true,
            'message' => 'Register Success',
            'data' => $success,
        ]);
    }

    public function login(Request $request) {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();
    
            $success['token'] = $auth->createToken('auth_token')->plainTextToken;
            $success['name'] = $auth->name;
    
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => $success
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'There’s something wrong here',
                'data' => null
            ]);
        }
    }
    
}
