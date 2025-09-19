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
}
