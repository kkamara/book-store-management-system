<?php

namespace App\Http\Controllers\V1\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use App\Models\V1\User;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\UserCollection;

class UserController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make(
            $request->only([
                'name', 'email', 'password', 'password_confirmation',
            ]),
            [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required|confirmed',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        if (null !== User::where($request->only('email'))->first()) {
            return response()->json(['email' => 'User with that email already exists'], Response::HTTP_BAD_REQUEST);
        }

        $user = (new User())->tap(function(User $user) use ($request) {
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();
            $user->token = $user->createToken('token')->plainTextToken;
        });

        return response()->json([
            'data' => new UserResource($user),
        ], Response::HTTP_CREATED);
    }

    function login(Request $request) {
        $validation = Validator::make(
            $request->only(['email', 'password',]),
            ['email' => 'required|email', 'password' => 'required',],
        );
        if($validation->fails()) {
            return response()->json($validation->errors(), Response::HTTP_BAD_REQUEST);
        }
        if (
            !Auth::attempt([
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ])
        ) {
            return response()->json([
                'Invalid email and password combination',
            ], Response::HTTP_BAD_REQUEST);
        }
        $user = User::where($request->only('email'))->firstOrFail();
        $user->tap(function(User $user) {
            $user->token = $user->createToken('token')->plainTextToken;
        });
        return response()->json([
            'data' => new UserResource($user),
        ], Response::HTTP_OK);
    }

    function authorizeUser(Request $request) {
        $user = User::where('email', $request->user()->email)->firstOrFail();

        return response()->json([
            'data' => new UserResource($user),
        ], Response::HTTP_ACCEPTED);
    }

    function logout(Request $request) {
        $request->user()
            ->currentAccessToken()
            ->delete();
        return ['message' => 'Success'];
    }

    public function getUsers(Request $request) {
        $data = User::orderBy("id", "DESC")
            ->paginate(7)
            ->appends($request->query());

        return new UserCollection($data);
    }

    public function account(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                "name" => ["sometimes", "required"],
                "email" => ["sometimes", "required"],
                "changePassword" => ["sometimes", "required", "required_with:changePasswordConfirmation", "same:changePasswordConfirmation"],
                "password" => ["required", "required_with:passwordConfirmation", "same:passwordConfirmation", "current_password"],
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $newUserFields = [];
        if ($request->input("name")) {
            $newUserFields["name"] = filter_var($request->input("name"), FILTER_SANITIZE_STRING);
        }
        if ($request->input("email")) {
            $newUserFields["email"] = filter_var($request->input("email"), FILTER_SANITIZE_STRING);
        }
        if ($request->input("changePassword")) {
            $newUserFields["password"] = Hash::make($request->input("changePassword"));
        }
        if (isset($newUserFields)) {
            auth()->user()->update($newUserFields);
        }
        $user = auth()->user();
        return new UserResource($user);
    }
}
