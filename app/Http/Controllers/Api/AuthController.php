<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    /**
     * Registers a new user
     *
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Authentication"},
     *     summary="Registers a new user",
     *     description="Registers a new user with the specified details",
     *     operationId="registerUser",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Fields needed to register a new user",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", minLength=8, example="secretpassword"),
     *             @OA\Property(property="confirm_password", type="string", example="secretpassword")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully registered."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors encountered during registration",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
     
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
     
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Logs in a user by providing email and password credentials",
     *     description="Logs in a user and retrieves an access token",
     *     tags={"Authentication"},
     *     @OA\Response(
     *         response="200",
     *         description="User logged in successfuly",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged in."),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6ImZhNjViODlk...[jwt access token]")
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Invalid email or password",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid email or password.")
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="The credential details of the user",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="john_doe@example.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
}
