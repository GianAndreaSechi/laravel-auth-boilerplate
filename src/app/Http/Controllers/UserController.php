<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

    class UserController extends Controller
    {
        /**
     * @OA\Post(
     *    path="/users/update",
     *    operationId="update",
     *    tags={"users"},
     *    summary="Update user password",
     *    description="Update user password",
     *    security={{"apiAuth":{}}},
     *    @OA\Parameter(name="password", in="query", description="user password", required=true,
     *        @OA\Schema(type="string")
     *    ),
     *    @OA\Parameter(name="password_confirmation", in="query", description="user password", required=true,
     *        @OA\Schema(type="string")
     *    ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="message",type="string", example="Password correctly updated!"),
     *             @OA\Property(property="user",type="object", example="<User Object>")
     *          ),
     *          response=404, description="User not found",
     *          response=400, description="Error"
     *       )
     *  )
     */
        public function updatePassword(Request $request)
        {

            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:6|confirmed'
            ]);

            if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
            }

            try {
                if (! $user_auth = JWTAuth::parseToken()->authenticate()) {
                        return response()->json(['user_not_found'], 404);
                }
                
                $user = User::where(
                    'email', $user_auth->email
                )->first();

                $new_password = Hash::make($request->get('password'));

                $user->update([
                    'password' => $new_password
                ]);
            } catch (JWTException $e) {
                    return response()->json(['token_absent'], $e->getStatusCode());
            }


            return response()->json(
                [
                    "message" => "Password correctly updated!",
                    "user" => $user_auth
                ]);

        }

        /**
     * @OA\Get(
     *    path="/users/",
     *    operationId="userInfo",
     *    tags={"users"},
     *    summary="Get authenticated user info",
     *    description="Get authenticated user info",
     *    security={{"apiAuth":{}}},
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="user",type="object", example="<User Object>")
     *          ),
     *          response=400, description="Error",
     *          response=401, description="Unauthenticated"
     *       )
     *  )
     */
        public function getAuthenticatedUser()
            {
                try {
                    if (! $user = JWTAuth::parseToken()->authenticate()) {
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

        /**
     * @OA\Get(
     *    path="/users/list",
     *    operationId="userList",
     *    tags={"users"},
     *    summary="Get user list",
     *    description="Get user list",
     *    security={{"apiAuth":{}}},
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="users",type="object", example="[<User Object>]")
     *          ),
     *          response=400, description="Error",
     *          response=401, description="Unauthenticated"
     *       )
     *  )
     */
        public function getUsers()
        {
            $users = User::all();

            return response()->json(["users"=>$users]);
        }


        /**
     * @OA\Get(
     *    path="/users/id/{id}",
     *    operationId="userById",
     *    tags={"users"},
     *    summary="Get user by id",
     *    description="Get user by id",
     *    security={{"apiAuth":{}}},
     *    @OA\Parameter(name="id", in="path", description="user id", required=true,
     *        @OA\Schema(type="integer")
     *    ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="user",type="object", example="<User Object>")
     *          ),
     *          response=404, description="User not found",
     *          response=400, description="Error"
     *       )
     *  )
     */
        public function getUserById($id)
        {
            $user = User::find($id);

            if (!isset($user)) {
                return response()->json(["message"=>"User id: ". $id ." doesn't exist"]); 
            }

            return response()->json($user);
        }

         /**
     * @OA\Get(
     *    path="/users/email/{email}",
     *    operationId="userByEmail",
     *    tags={"users"},
     *    summary="Get user by email",
     *    description="Get user by email",
     *    security={{"apiAuth":{}}},
     *    @OA\Parameter(name="email", in="path", description="user email", required=true,
     *        @OA\Schema(type="string")
     *    ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="user",type="object", example="<User Object>")
     *          ),
     *          response=404, description="User not found",
     *          response=400, description="Error"
     *       )
     *  )
     */
        public function getUserByEmail($email)
        {
            $user = User::where('email', $email)->first();

            if (!isset($user)) {
                return response()->json(["message"=>"User Email: ". $email ." doesn't exist"]); 
            }

            return response()->json($user);
        }
    }
