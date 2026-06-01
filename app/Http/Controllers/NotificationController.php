<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Add markAllRead() method — sets all of the user's notifs is_read = true
     */
    public function markAllRead()
    {
        DB::table('notifs')
            ->where('user_id', auth()->id())
            ->update(['is_read' => true]);

        return back();
    }
}