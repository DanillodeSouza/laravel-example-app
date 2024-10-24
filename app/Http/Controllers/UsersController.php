<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        return User::query()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|unique:users,email,',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return new JsonResponse(
                [
                    'errors' => $validator->errors(),
                ],
                422
            );
        }

        return User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password'))
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Redis::get('user_' . $id);

        if ($user) {
            return new JsonResponse(json_decode($user));
        }

        $user = User::query()->where(['id' => $id])->get()->first();
        if ($user) {
            Redis::set('user_' . $id, json_encode($user));
            return $user;
        }

        return new JsonResponse(['message' => 'Not Found.'], 404);
        //return response()->json(['message' => 'not found.'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
