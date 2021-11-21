<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;

use App\Models\User;

class UserController extends Controller
{
    public function authenticate(Request $request){
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials) ) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }

        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                $msg = 'user_not_found';

                return response()->json(compact('msg'), 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $msg = 'user_not_found';

            return response()->json(compact('msg'), $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $msg = 'user_not_found';

            return response()->json(compact('msg'), $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            $msg = 'user_not_found';

            return response()->json(compact('msg'), $e->getStatusCode());
        }
        
        return response()->json(compact('user'));
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            $msg = $validator->errors()->toJson();
            return response()->json(compact('msg'), 400);
        }

        $data = User::create([
            'name'     => $request->get('name'),
            'email'    => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $data->token = JWTAuth::fromUser($data);

        return response()->json(compact('data'),201);
    }

    public function logout(Request $request){
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            
        } catch (JWTException $e) {
            \Log::error($e);
            $msg = 'could_not_close_session';

            return response()->json(compact('msg'), 500);
        }

        $msg = 'session has been closed';

        return response()->json(compact('msg'), 200);
    }
}
