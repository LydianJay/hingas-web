<?php

namespace App\Http\Controllers;

use App\Models\Dance;
use App\Models\DanceSession;
use App\Models\Enrollment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use function PHPUnit\Framework\isEmpty;

class ESP32API extends Controller
{
    

    public function rfid(Request $request) {


        $rfid       = $request->input('rfid');


        if(!$rfid || $rfid == '') {
            // File::put(storage_path('logs/esp32.log'), Carbon::now()->toDateTimeString() . ' [INVALID RFID] ' . ' - ' . $rfid . PHP_EOL, FILE_APPEND);
            return response()->json(['msg' => 'Invalid RFID'], 400);
        }

        $enrollment = User::where('rfid', $rfid)
                    ->join('enrollment', 'enrollment.user_id', '=', 'users.id')
                    ->where('users.is_active', 1)
                    ->select( 'enrollment.dance_id', 'enrollment.id')
                    ->first();
        // return response()->json(json_encode($enrollment));
        
        if(!$enrollment || $enrollment == null) {
            
            // File::put(storage_path('logs/esp32.log'), Carbon::now()->toDateTimeString() . ' - ' . $rfid . PHP_EOL, FILE_APPEND);
            return response()
            ->json([
                'msg' => 
                'No record found!'], 
            404);

        }

        // Lets check if the user already exhausted hes/her sessions

        $sesCount   = DanceSession::where('enrollment_id', $enrollment->id)
                    ->whereNotNull('time_out')
                    ->count();

        $dance      = Dance::where('id', $enrollment->dance_id)
                    ->select('session_count')
                    ->first();
        
        if($sesCount >= $dance->session_count) {


            Enrollment::find($enrollment->id)
            ->update(['is_active' => 0]);


            return response()
            ->json([
                'msg' => 
                'You have already used all of your sessions!'], 
            400);
        }



        $dance_ses  = DanceSession::where('enrollment_id', $enrollment->id)
                    ->where('date', Carbon::today()->toDateString())
                    ->first();
        // return response()->json(json_encode($dance_ses));
        
        if($dance_ses && $dance_ses != null) {
            

            if($dance_ses->time_out != null) {
                // File::put(storage_path('logs/esp32_attendance.log'), Carbon::now()->toDateTimeString() . ' - ' . $rfid . PHP_EOL, FILE_APPEND);
                return response()
                ->json([
                    'msg' => 'You already have session for this day'
                ], 400);
            }





            $dance_ses->time_out = Carbon::now()->format('H:i:s');
            $dance_ses->save();

        }
        else {


            DanceSession::create([
                'enrollment_id' => $enrollment->id,
                'time_in'       => Carbon::now()->format('H:i:s'),
                'date'          => Carbon::today()->toDateString(),
            ]);
            
        }

        
        

        return response()
        ->json([
            'msg' => 'Session Added'
        ]);


    }

}
