<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Add index() method — queries all stats and passes them to admin.dashboard view
     */
    public function index()
    {
        return view('admin.dashboard', [
            'totalPets'           => DB::table('pets')->count(),
            'availablePets'       => DB::table('pets')->where('status', 'available')->count(),
            'pendingRequests'     => DB::table('adoption_requests')->where('status', 'pending')->count(),
            'adoptedPets'        => DB::table('pets')->where('status', 'adopted')->count(),
            'pendingRequestsList' => DB::table('adoption_requests')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($r) {
                    $r->pet  = DB::table('pets')->where('id', $r->pet_id)->first();
                    $r->user = DB::table('users')->where('id', $r->user_id)->first();
                    return $r;
                }),
            'allRequests'         => DB::table('adoption_requests')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($r) {
                    $r->pet  = DB::table('pets')->where('id', $r->pet_id)->first();
                    $r->user = DB::table('users')->where('id', $r->user_id)->first();
                    return $r;
                }),
            'pets'                => DB::table('pets')->orderBy('created_at', 'desc')->get(),
            'recentNotifications' => DB::table('notifs')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function ($n) {
                    $n->user = DB::table('users')->where('id', $n->user_id)->first();
                    return $n;
                }),
        ]);
    }

    /**
     * Add approve() method — updates request status, inserts notification, updates pet status
     */
    public function approve($id)
    {
        DB::table('adoption_requests')->where('id', $id)->update([
            'status'      => 'approved',
            'reviewed_at' => now(),
            'updated_at'  => now(),
        ]);

        $request = DB::table('adoption_requests')->where('id', $id)->first();

        DB::table('notifs')->insert([
            'user_id'             => $request->user_id,
            'adoption_request_id' => $id,
            'title'               => 'Adoption Request Approved! 🎉',
            'message'             => 'Congratulations! Your adoption request has been approved. Please visit us to complete the process.',
            'type'                => 'approved',
            'is_read'             => false,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        DB::table('pets')->where('id', $request->pet_id)->update(['status' => 'pending']);

        session()->flash('success', 'Adoption request approved and applicant notified!');
        return back();
    }

    /**
     * Add reject() method — updates request status, inserts rejection notification
     */
    public function reject($id)
    {
        DB::table('adoption_requests')->where('id', $id)->update([
            'status'      => 'rejected',
            'reviewed_at' => now(),
            'updated_at'  => now(),
        ]);

        $request = DB::table('adoption_requests')->where('id', $id)->first();

        DB::table('notifs')->insert([
            'user_id'             => $request->user_id,
            'adoption_request_id' => $id,
            'title'               => 'Adoption Request Update',
            'message'             => 'Thank you for your interest. Unfortunately your adoption request was not approved at this time. Please feel free to apply for another pet.',
            'type'                => 'rejected',
            'is_read'             => false,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        session()->flash('success', 'Adoption request rejected and applicant notified.');
        return back();
    }

    /**
     * Add destroy() method — deletes a pet record from the database
     */
    public function destroy($id)
    {
        DB::table('pets')->where('id', $id)->delete();
        session()->flash('success', 'Pet removed successfully.');
        return back();
    }
}