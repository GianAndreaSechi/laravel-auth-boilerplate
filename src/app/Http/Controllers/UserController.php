<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

    class UserController extends Controller
    {
        public function authenticate(Request $request)
        {
            $credentials = $request->only('email', 'password');

            try {
                if (! $token = FacadesJWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 400);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            return response()->json(compact('token'));
        }

        public function register(Request $request)
        {
                $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
            }

            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            $token = FacadesJWTAuth::fromUser($user);

            return response()->json(compact('user','token'),201);
        }

        public function getAuthenticatedUser()
            {
                try {
                    if (! $user = FacadesJWTAuth::parseToken()->authenticate()) {
                            return response()->json(['user_not_found'], 404);
                    }
                } catch (TokenExpiredException $e) {
                        return response()->json(['token_expired'], $e->getStatusCode());
                } catch (TokenInvalidException $e) {
                        return response()->json(['token_invalid'], $e->getStatusCode());
                } catch (JWTException $e) {
                        return response()->json(['token_absent'], $e->getStatusCode());
                }

                return response()->json(compact('user'));
        }

        public function getUsers()
        {
            $users = User::all();

            return response()->json($users);
        }

        public function getUserById($id)
        {
            $user = User::find($id);

            if (!isset($user)) {
                return response()->json(["message"=>"User id: ". $id ." doesn't exist"]); 
            }

            return response()->json($user);
        }

        public function getUserByEmail($email)
        {
            $user = User::where('email', $email)->first();

            if (!isset($user)) {
                return response()->json(["message"=>"User Email: ". $email ." doesn't exist"]); 
            }

            return response()->json($user);
        }
    }
