<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  public function index() { return UserResource::collection(User::latest()->get()); }
    public function store(Request $request) {
        $validated = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6'
        ]);
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        return new UserResource($user);
    }
    public function show(User $user) { return new UserResource($user); }
    public function update(Request $request, User $user) {
        $validated = $request->validate([
            'name'=>'sometimes|string|max:255',
            'email'=>'sometimes|email|unique:users,email,'.$user->id,
        ]);
        $user->update($validated);
        return new UserResource($user);
    }
    public function destroy(User $user) { $user->delete(); return response()->json(['message'=>'User deleted']); }
}
