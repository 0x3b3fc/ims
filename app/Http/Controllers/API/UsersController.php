<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::with('roles.permissions')->get();
        return $this->sendResponse($users->toArray(), 'Users retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
            'password' => 'required',
        ]);
        $data['email_verified_at'] = now();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        $user->addRole($data['role']);
        return $this->sendResponse($user->load('roles.permissions')->toArray(), 'User created successfully.');
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return $this->sendError('User not found.');
        }
        return $this->sendResponse($user->toArray(), 'User retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
            'password' => 'required',
        ]);
        $data['email_verified_at'] = now();
        $data['password'] = bcrypt($data['password']);
        $user->update($data);
        $user->syncRoles([$data['role']]);
        return $this->sendResponse($user->load('roles.permissions')->toArray(), 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        $user->removeRoles($user->roles);
        $user->delete();
        return $this->sendResponse($user->toArray(), 'User deleted successfully.');
    }

    /**
     * Login API
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $input = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($input)) {
            return $this->sendError('Unauthorized.', ['error' => 'Unauthorized']);
        }
        $user = auth()->user();
        $token = $user->createToken('ims-2025')->plainTextToken;
        return $this->sendResponse(['token' => $token], 'User logged in successfully.');
    }

    /**
     * Logout API
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse([], 'User logged out successfully.');
    }

    /**
     * User profile API
     * @return JsonResponse
     */
    public function profile()
    {
        return $this->sendResponse(auth()->user()->load('roles.permissions')->toArray(), 'User profile retrieved successfully.');
    }
}
