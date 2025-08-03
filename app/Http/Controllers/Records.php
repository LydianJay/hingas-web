<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\DanceSession;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;

class Records extends Controller
{
    

    public function attendance(Request $request) {
        // This method will handle attendance records
        // Implementation goes here

        $data       = [];
        $page       = $request->input('page', 1);
        $perPage    = 12;



        $data['attendance']     = DanceSession::join('enrollment', 'dance_session.enrollment_id', '=', 'enrollment.id')
                                ->join('users', 'enrollment.user_id', '=', 'users.id')
                                ->join('dance', 'enrollment.dance_id', '=', 'dance.id')
                                ->orderBy('date', 'desc')
                                ->get();

        $data['page']       = $page;
        $data['perPage']    = $perPage;
        $data['count']      = Cache::remember('session_count', 60, function () {
                                 return DanceSession::count();
                             });
        $data['totalPages'] = ceil($data['count'] / $perPage);

        // dd($data['attendance']);
        
        return view('pages.records.attendance', $data);
    }
}
