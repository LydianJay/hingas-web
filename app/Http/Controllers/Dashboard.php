<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DanceSession;
use App\Models\Enrollment;
use App\Models\Dance;

class Dashboard extends Controller
{
    

    public function index() {


        $data['totalUsers']             = User::leftJoin('admin', 'admin.id', '=', 'users.id')
                                        ->whereNull('admin.user_id')
                                        ->where('users.is_active', 1)
                                        ->count();
        $data['totalEnrollments']       = Enrollment::count();
        $data['totalDanceSessions']     = DanceSession::count();
        $data['totalDances']            = Dance::where('is_active', 1)->count();



        return view('pages.dashboard.view', $data);

    }


    





}
