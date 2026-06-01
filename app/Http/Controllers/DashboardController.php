<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Add index() method — fetches the auth user's requests, notifications, unread count
     * Pass all data to the dashboard view
     */
    public function index()
    {
        $user = auth()->user();

        $requests = DB::table('adoption_requests')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($r) {
                $r->pet = DB::table('pets')->where('id', $r->pet_id)->first();
                return $r;
            });

        $notifications = DB::table('notifs')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadNotifications = $notifications->where('is_read', false)->count();

        return view('dashboard', [
            'requests'            => $requests,
            'notifications'       => $notifications,
            'unreadNotifications' => $unreadNotifications,
        ]);
    }
}