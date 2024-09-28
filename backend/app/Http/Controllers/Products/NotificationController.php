<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;
use App\Traits\CrudOperationsTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class NotificationController extends Controller
{
   /*
    |--------------------------------------------------------------------------
    | Display a listing of the notifications for the current user.
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $userId = Auth::id();
        $notifications = User::find($userId)->notifications; // استخدام العلاقة لتصفية الإشعارات

        return NotificationResource::collection($notifications);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created notification in storage.
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validationRules = [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|max:50',
        ];

        $validatedData = $request->validate($validationRules);
        
        $notification = Notification::create($validatedData);
        
        Auth::user()->notifications()->attach($notification->id, ['read_at' => false]);

        return new NotificationResource($notification);
    }

    /*
    |--------------------------------------------------------------------------
    | Display the specified notification.
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $notification = Notification::findOrFail($id);

        if (!Auth::user()->notifications()->where('notification_id', $id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return new NotificationResource($notification);
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified notification in storage.
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);

        if (!Auth::user()->notifications()->where('notification_id', $id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validationRules = [
            'title' => 'sometimes|required|string|max:255',
            'message' => 'sometimes|required|string',
            'type' => 'sometimes|required|string|max:50',
        ];

        $validatedData = $request->validate($validationRules);
        
        $notification->update($validatedData);

        return new NotificationResource($notification);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified notification from storage.
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);

        if (!Auth::user()->notifications()->where('notification_id', $id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        Auth::user()->notifications()->detach($notification->id);

        return response()->json(['message' => 'Notification deleted successfully'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | Clear all notifications for the current user.
    |--------------------------------------------------------------------------
    */
    public function clearAll()
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        Auth::user()->notifications()->detach();
        
        return response()->json(['message' => 'All notifications deleted successfully'], 200);
    }
}
