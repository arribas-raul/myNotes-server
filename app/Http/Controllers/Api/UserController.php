<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use App\Helpers\LogHelper;

use App\Models\User;

class UserController extends Controller
{
    public function authenticate(Request $request){
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials) ) {
                return response()->json(['error' => 'invalid_credentials'], 404);
            }

            return response()->json(compact('token'), 200);

        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'user_not_found'], 404);
            }

            $email = $user->email;

            return response()->json(compact('email', 'user'), 200);

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode());
        }  
    }

    public function checkSession(Request $request){
        $data = $request->user;

        $user = new \stdClass();
        $user->name = $data->name;
        $user->email = $data->email;

        return response()->json(compact('user'), 200);
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            if($validator->fails()){
                \Log::error(['validator'=> $validator->errors()->toJson()]);
                return response()->json(['error' => $validator->errors()->toJson()], 400);
            }

            $data = User::create([
                'name'     => $request->get('name'),
                'email'    => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            $token = JWTAuth::fromUser($data);
            $email = $data->email;

            return response()->json(compact('email', 'token'), 201);

        }catch (\Exception $e){
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode()); 
                
        }catch (\PDOException $e){
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode());           
        } 
    }

    public function logout(Request $request){
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            $msg = 'Logout success!';

            return response()->json(compact('msg'), 200);
            
        } catch (JWTException $e) {
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => 'could_not_close_session'], 500);
        }

        $msg = 'session has been closed';

        return response()->json(compact('msg'), 200);
    }
}
