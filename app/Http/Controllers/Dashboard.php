<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DanceSession;
use App\Models\Enrollment;
use App\Models\Dance;
use App\Models\Payments;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{
    

    public function index() {


        $data['totalUsers']             = User::leftJoin('admin', 'admin.id', '=', 'users.id')
                                        ->whereNull('admin.user_id')
                                        ->where('users.is_active', 1)
                                        ->count();
        $data['totalEnrollments']       = Enrollment::where('is_active', 1)->count();
        $data['totalDanceSessions']     = DanceSession::count();
        $data['totalDances']            = Dance::where('is_active', 1)->count();

        $monthlyPayments                = Payments::select(
                                            DB::raw('MONTH(date) as month'),
                                            DB::raw('YEAR(date) as year'),
                                            DB::raw('SUM(amount) as total')
                                        )
                                        ->groupBy('year', 'month')
                                        ->orderBy('year')
                                        ->orderBy('month')
                                        ->get();

    $data['paymentMonths']              = $monthlyPayments->map(function ($item) {
                                            return Carbon::createFromDate($item->year, $item->month, 1)->format('M Y');
                                        });

    $data['paymentTotals']              = $monthlyPayments->pluck('total');

        return view('pages.dashboard.view', $data);

    }


    





}
