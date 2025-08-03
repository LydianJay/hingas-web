<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class Dashboard extends Controller
{
    

    public function index() {


        $data['totalUsers'] = User::count();
        $data['totalEnrollments'] = \App\Models\Enrollment::count();
        $data['totalDanceSessions'] = \App\Models\DanceSession::count();
        $data['totalDances'] = \App\Models\Dance::count();

        return view('pages.dashboard.view', $data);

    }

}
