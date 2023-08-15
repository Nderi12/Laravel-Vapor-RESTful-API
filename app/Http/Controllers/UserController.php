<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/users",
     *      operationId="getUsersList",
     *      summary="Get list of Users",
     *      description="Returns list of users",
     *      tags={"Users"},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      	  @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="users",
     *                  collectionFormat="multi"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Users not found"
     *      )
     * )
     */
    public function index()
    {
        $users = User::all();

        if (is_null($users)) {
            return response()->json([
                'message' => 'Users not found!'
            ]);
        }

        return response()->json([
            'user' => $users
        ]);

    }

    /**
     * Create a new user
     *
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Create a new user",
     *     description="Create a new user with the specified details",
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::transaction(function() use($request) {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hash password
            ]);

            dispatch(new SendEmailJob($request->email));
        });

        return response()->json([
            'message' => 'User created successfully'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get a single user by id",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id of the user to get",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="id"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User not found"
     *     )
     * )
     */
    public function show(User $user)
    {
        if (is_null($user)) {
            return $this->sendError('Product not found.');
        }

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update a user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id of the user to update",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="id"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     oneOf={
     *                     	   @OA\Schema(type="string"),
     *                     	   @OA\Schema(type="integer"),
     *                     }
     *                 ),
     *                 @OA\Property(
     *                     property="metadata",
     *                     oneOf={
     *                     	   @OA\Schema(type="json"),
     *                     	   @OA\Schema(type="integer"),
     *                     }
     *                 ),
     *                 example={"name": "User Name"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User updated successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User not found"
     *     )
     * )
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::transaction(function() use($request, $user) {
            $user->update([
                "name" => $request->name ?? '',
                "email" => $request->email ?? '',
                'password' => Hash::make($request->password) ?? '', // Hash password
            ]);
        });

        return response()->json([
            'message' => 'User updated successfully'
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id of the user to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="id"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User not found"
     *     )
     * )
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}