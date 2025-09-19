<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DanceSession;

class RFID extends Controller
{



    public function status(Request $request) {




        return view('pages.rfid.view');

    }


    // ====================== API ========================
    public function get_latest_user(Request $request) {


        $rfid = $request->input('rfid');


        $user   = DanceSession::join('enrollment','enrollment.id','=','dance_session.enrollment_id')
                ->join('users','users.id','=','enrollment.user_id')
                ->join('dance', 'dance.id', '=', 'enrollment.dance_id')
                ->when($rfid, function ($query) use ($rfid) {
                    $query->where('users.rfid','=', $rfid);
                })
                ->orderBy('dance_session.id','desc')
                ->first();




        return response()->json($user);
    }
}
