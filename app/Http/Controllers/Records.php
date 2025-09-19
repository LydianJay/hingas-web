<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Payments;
use App\Models\DanceSession;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                                ->limit($perPage)
                                ->offset(($page - 1) * $perPage)
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



    public function fee_collection(Request $request) {


        $data       = [];
        $page       = $request->input('page', 1);
        $perPage    = 12;


        $payments       = Payments::join('enrollment', 'enrollment.id', '=', 'payments.enrollment_id')
                        ->join('users', 'users.id','=', 'enrollment.user_id')
                        ->join('dance', 'dance.id', '=', 'enrollment.dance_id')
                        ->select([
                            'users.fname',
                            'users.lname',
                            'payments.amount',
                            'dance.name',
                            'payments.date'
                        ])
                        ->limit($perPage)
                        ->offset(($page - 1) * $perPage)
                        ->orderBy('date', 'desc')
                        ->get();


        // dd($payments);
        $data['payments']   = $payments;
        $data['page']       = $page;
        $data['perPage']    = $perPage;
        $data['count']      = Payments::count();
        $data['totalPages'] = ceil($data['count'] / $perPage);

        return view('pages.records.fee_collection', $data);
    }


    public function enrollment(Request $request) {
        $data       = [];
        $page       = $request->input('page', 1);
        $perPage    = 12;


        $enrollments = User::join('enrollment', 'enrollment.user_id', '=', 'users.id')
                    ->join('dance', 'dance.id', '=', 'enrollment.dance_id')
                    ->leftJoin('dance_session', 'dance_session.enrollment_id', '=', 'enrollment.id')
                    ->leftJoin('payments', 'payments.enrollment_id', '=', 'enrollment.id')
                    ->select([
                        'users.fname',
                        'users.lname',
                        'dance.name',
                        'dance.price',
                        db::raw('COUNT(dance_session.id) as ses'),
                        db::raw('SUM(payments.amount) as paid'),

                    ])
                    ->limit($perPage)
                    ->offset(($page - 1) * $perPage)
                    ->groupBy('enrollment.id')
                    ->get();


        $data['enrollments']    = $enrollments;
        $data['page']           = $page;
        $data['perPage']        = $perPage;

        $data['count']          = User::join('enrollment', 'enrollment.user_id', '=', 'users.id')
                                ->join('dance', 'dance.id', '=', 'enrollment.dance_id')
                                ->leftJoin('dance_session', 'dance_session.enrollment_id', '=', 'enrollment.id')
                                ->count();


        $data['totalPages']     = ceil($data['count'] / $perPage);


        return view('pages.records.enrollment', $data);

    }



    // ================ API ==============================


    public function get_csv_attendance() {


        $attendances    = DanceSession::join('enrollment', 'dance_session.enrollment_id', '=', 'enrollment.id')
                        ->join('users', 'enrollment.user_id', '=', 'users.id')
                        ->join('dance', 'enrollment.dance_id', '=', 'dance.id')
                        ->orderBy('date', 'desc')
                        ->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=attendance.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        // Callback to write CSV content
        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');
            
            // CSV column titles
            fputcsv($file, ['First Name', 'Last Name', 'Dance', 'Time In', 'Time Out']);
            
            // CSV rows
            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->fname,
                    $attendance->lname,
                    $attendance->name,
                    $attendance->time_in,
                    $attendance->time_out,
                ]);
            }
            
            fclose($file);
        };


        return response()->stream($callback, 200, $headers);

    }

    public function get_csv()
    {
            $attendances = User::leftJoin('enrollment', 'enrollment.user_id', '=', 'users.id')
            ->leftJoin('payments', 'payments.enrollment_id', '=', 'enrollment.id')
            ->leftJoin('dance', 'dance.id', '=', 'enrollment.dance_id')
            ->select([
                'users.fname', 
                'users.lname', 
                'users.gender', 
                'users.dob', 
                'users.address',
                'users.contactno',
                DB::raw('SUM(payments.amount) as amount'),
                DB::raw('SUM(price) as price'),
            ])
            ->leftJoin('admin', 'admin.user_id', '=', 'users.id')
            ->whereNull('admin.id')
            ->groupBy('users.id')
            ->where('users.is_active', 1)
            ->get();

        // Create CSV headers
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=balance.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        // Callback to write CSV content
        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');
            
            // CSV column titles
            fputcsv($file, ['First Name', 'Last Name', 'Gender', 'DOB', 'Address', 'Contact No', 'Total Paid', 'Total Price']);
            
            // CSV rows
            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->fname,
                    $attendance->lname,
                    $attendance->gender,
                    $attendance->dob,
                    $attendance->address,
                    $attendance->contactno,
                    $attendance->amount,
                    $attendance->price,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
