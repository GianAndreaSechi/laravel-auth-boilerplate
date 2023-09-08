<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    /**
     * @OA\Post(
     *    path="/signup",
     *    operationId="signup",
     *    tags={"authentication"},
     *    summary="Sign up user",
     *    description="Sign up user",
     *    @OA\Parameter(name="name",description="Username",required=true,in="query",
     *          @OA\Schema(type="string")
     *      ),
     *    @OA\Parameter(name="email",description="user email",required=true,in="query",
     *          @OA\Schema(type="string")
     *      ),
     *    @OA\Parameter(name="password", in="query", description="user password", required=true,
     *        @OA\Schema(type="string")
     *    ),
     *    @OA\Parameter(name="password_confirmation", in="query", description="user password", required=true,
     *        @OA\Schema(type="string")
     *    ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="user",type="object", example="<User Object>"),
     *             @OA\Property(property="token",type="string", example="<JWT Token>")
     *          ),
     *          response=400, description="Error"
     *       )
     *  )
     */
    public function signup(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);
    
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
    
        $token = JWTAuth::fromUser($user);
    
        return response()->json(['token' => $token], 201);
    }
    
    /**
     * @OA\Post(
     *    path="/login",
     *    operationId="authenticate",
     *    tags={"authentication"},
     *    summary="Get user JWT authentication",
     *    description="Get user JWT authentication",
     *    @OA\Parameter(name="email",description="User email",required=true,in="query",
     *          @OA\Schema(type="string")
     *      ),
     *    @OA\Parameter(name="password", in="query", description="User password", required=true,
     *        @OA\Schema(type="string")
     *    ),
     *    @OA\Parameter(name="isWeb", in="query", description="Define if it's a webform request", required=false,
     *        @OA\Schema(type="bool")
     *    ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="token",type="string", example="<JWT Token>")
     *          )
     *       )
     *  )
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $isWeb = $request->get('isWeb');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        if($isWeb){
            Auth::attempt($credentials);
            $user = Auth::user();

            //save session if is webform
            Session::put('jwt_token', $token);

            return redirect()->intended('/');
        } else {
            return response()->json(compact('token'));
        }
        
        //return response()->json(compact('token'));
    }
    

    /**
     * @OA\Post(
     *    path="/logout",
     *    operationId="logout",
     *    tags={"authentication"},
     *    summary="Logout user",
     *    description="Logout user",
     *    security={{"apiAuth":{}}},
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
    public function logout(Request $request)
    {
        // Maybe set below to `false`,
        // else cache may take too much storage.
        $forever = true;

        if($request->isMethod('post')){
            // Both loads and blacklists
            // (the token, if it's set, else may raise exception).
            JWTAuth::parseToken()->invalidate( $forever );
            return response()->json(['message' => 'Successfully logged out']);
        } else {
            Session::forget('jwt_token');
            Auth::logout();

            return redirect()->intended('/login');
        }
    }
}
