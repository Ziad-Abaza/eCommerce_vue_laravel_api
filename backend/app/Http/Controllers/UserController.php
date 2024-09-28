<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\CrudOperationsTrait;
use App\Traits\HandleFile;

class UserController extends Controller
{
    use CrudOperationsTrait, HandleFile;

    /*
    |--------------------------------------------------------------------------
    | Display the currently authenticated user.
    |--------------------------------------------------------------------------
    */
    public function profile()
    {
        $user = User::with(['orders','purchases', 'favorites'])
                    ->findOrFail(Auth::id());

        return new UserResource($user);
    }

    /*
    |--------------------------------------------------------------------------
    | Update the currently authenticated user's profile.
    |--------------------------------------------------------------------------
    */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ];

        $validationError = $this->validateRequestData($request, $validationRules);
        if ($validationError) return $validationError;

        $data = $request->only(['name', 'email', 'phone', 'address']);

        $user = $this->updateRecord(new User(), $user->id, $data);

        return new UserResource($user);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete the currently authenticated user's profile.
    |--------------------------------------------------------------------------
    */
    public function destroy()
    {
        $user = Auth::user();
        $this->deleteRecord(new User(), $user->id);
        return response()->json(['message' => 'User account deleted successfully'], 200);
    }
}
