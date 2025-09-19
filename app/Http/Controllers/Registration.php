<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use App\Models\User;
use App\Models\Dance;
use App\Models\DanceSession;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Carbon\Carbon;
use function PHPUnit\Framework\isEmpty;

class Registration extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->input('search');
        $page       = $request->input('page', 1);
        $perPage    = 12;


        $data['dances'] = Dance::all();

        $data['users']  = User::leftJoin('admin', 'admin.id', '=', 'users.id')
                        ->leftJoin('enrollment', function($join) {
                            $join->on('enrollment.user_id', '=', 'users.id')
                                ->where('enrollment.is_active', 1);
                        })
                        ->leftJoin('dance', 'dance.id', '=', 'enrollment.dance_id')
                        ->when($search, function ($query) use ($search) {
                            $query->where(function ($subquery) use ($search) {
                                $subquery->where('users.fname', 'LIKE', '%' . $search . '%')
                                        ->orWhere('users.lname', 'LIKE', '%' . $search . '%');
                            });
                        })
                        ->whereNull('admin.user_id')
                        ->where('users.is_active', 1)
                        ->offset(($page - 1) * $perPage)
                        ->limit($perPage)
                        ->select(['users.*', 'enrollment.id as e_id', 'dance.name as d_name'])
                        ->get();
        // dd($data['users']);


        
        $data['page']           = $page;
        $data['perPage']        = $perPage;
        $data['count']          = User::leftJoin('admin', 'admin.id', '=', 'users.id')
                                ->whereNull('admin.user_id')
                                ->where('users.is_active', 1)
                                ->count();

        

        $data['totalPages']         = ceil($data['count'] / $perPage);
        $data['search']             = $search;


        return view('pages.registration.view', $data);
    }

    public function register(Request $request) {
        $validated = $request->validate([
            'rfid'           => 'nullable|string',
            'email'          => 'required|email|unique:users,email',
            'fname'          => 'required|string|max:100',
            'mname'          => 'nullable|string|max:100',
            'lname'          => 'required|string|max:100',
            'gender'         => 'required|in:male,female,other',
            'dob'            => 'required|date|before:today',
            'contactno'      => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'e_contact'      => 'nullable|string|max:100',
            'e_contact_no'   => 'nullable|string|max:20',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);



        // Handle file upload
        
        DB::beginTransaction();

        try {

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos', 'public');
            }

            // Create the user
            User::create([
                'rfid'            => ltrim($validated['rfid'], 0),
                'email'           => $validated['email'],
                'fname'           => $validated['fname'],
                'mname'           => $validated['mname'],
                'lname'           => $validated['lname'],
                'gender'          => $validated['gender'],
                'dob'             => $validated['dob'],
                'contactno'       => $validated['contactno'],
                'address'         => $validated['address'],
                'e_contact'       => $validated['e_contact'],
                'e_contact_no'    => $validated['e_contact_no'],
                'photo'           => $photoPath,
            ]);

           


            DB::commit();

            return redirect()->back()->with('status', [
                'alert' => 'alert-success',
                'msg'   => 'User data added!',
            ]);


        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', [
                'alert' => 'alert-danger',
                'msg'   => $e->getMessage(),
            ]);

        }

        

    }


    public function edit(Request $request) {


        $validated = $request->validate([
            'id'             => 'required',
            'rfid'           => 'nullable|string',
            'email'          => 'required|email',
            'fname'          => 'required|string|max:100',
            'mname'          => 'nullable|string|max:100',
            'lname'          => 'required|string|max:100',
            'gender'         => 'required|in:male,female,other',
            'dob'            => 'required|date|before:today',
            'contactno'      => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'e_contact'      => 'nullable|string|max:100',
            'e_contact_no'   => 'nullable|string|max:20',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);



        DB::beginTransaction();

        try {

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos', 'public');
            }

            // Create the user
            $user = User::find($validated['id']);
           
            if(!$user) {
                DB::rollBack();
                return redirect()
                ->route('registration')
                        ->with('status', [
                    'alert' => 'alert-danger',
                    'msg'   => 'Invalid user!',
                ]);
            }


            $user->update([
                'rfid'            => ltrim($validated['rfid'], 0),
                'email'           => $validated['email'],
                'fname'           => $validated['fname'],
                'mname'           => $validated['mname'],
                'lname'           => $validated['lname'],
                'gender'          => $validated['gender'],
                'dob'             => $validated['dob'],
                'contactno'       => $validated['contactno'],
                'address'         => $validated['address'],
                'e_contact'       => $validated['e_contact'],
                'e_contact_no'    => $validated['e_contact_no'],
            ]);

            if($photoPath) {
                $user->update(['photo' => $photoPath]);
            }
            

            


            DB::commit();

            return redirect()->route('registration')->with('status', [
                'alert' => 'alert-success',
                'msg'   => 'User data edited!',
            ]);


        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', [
                'alert' => 'alert-danger',
                'msg'   => $e->getMessage(),
            ]);

        }

    }



    public function enroll(Request $request) {


        

        $validated      = $request->validate([
                            'id'        => 'required', // User id
                            'dance'     => 'required',
                            'payment'    => 'nullable' // initial payment
                        ]);
        
        
        
        // Check if already enrolled
        $enrolled   = Enrollment::where('user_id', $validated['id'])
                    ->where('dance_id', $validated['dance'])
                    ->where('is_active', 1)
                    ->exists();

        if($enrolled) {
            return redirect()->route('registration')->with('status', [
                'alert' => 'alert-danger',
                'msg'   => 'Already enrolled!',
            ]);
        }

        db::beginTransaction();
        try {




            $enrollment = Enrollment::create([
                'dance_id'  => $validated['dance'],
                'user_id'   => $validated['id'],
            ]);
            
    
            if(isset($validated['payment']) && $validated['payment'] > 0) {
                $dance_details = Dance::find($validated['dance']);
                if($validated['payment'] > $dance_details->price) {
                    db::rollBack();
                    return redirect()
                    ->route('registration')
                    ->with('status', [
                        'alert' => 'alert-danger',
                        'msg'   => 'Your payment amount is greater than the price, Please try again',
                    ]);
                }


                Payments::create([
                    'enrollment_id' => $enrollment->id,
                    'admin_id'      => Auth::user()->id,
                    'user_id'       => $validated['id'],
                    'amount'        => $validated['payment'],
                    'date'          => Carbon::now()->toDateString(),
                ]);
    
            }

            

        } catch(Throwable $e) {
            db::rollBack();
            return redirect()->route('registration')->with('status', [
                'alert' => 'alert-danger',
                'msg'   => $e->getMessage(),
            ]);
        }


        db::commit();
        return redirect()->route('registration')->with('status', [
            'alert' => 'alert-success',
            'msg'   => 'User enrolled!',
        ]);
        
        

    }



    public function collect_fee(Request $request) {
        $validated      = $request->validate([
            'id'        => 'required', 
            'amount'    => 'required' 
        ]);
        

        $enrolled  = Enrollment::where('enrollment.user_id', $validated['id'])
                    ->leftJoin('payments', 'payments.enrollment_id', '=', 'enrollment.id')
                    ->join('dance', 'dance.id', '=', 'enrollment.dance_id')
                    ->select(
                        'enrollment.id as enrollment_id',
                        DB::raw('COALESCE(SUM(payments.amount), 0) as total_paid'),
                        'dance.price as dance_price'
                    )
                    ->groupBy('enrollment.id', 'dance.price')
                    ->havingRaw('total_paid < dance_price')
                    ->first();

        if(!$enrolled) {
            return redirect()
            ->route('registration')
            ->with('status', [
                'alert' => 'alert-danger',
                'msg'   => 'Your dont have fees!!!',
            ]);
        }

        db::beginTransaction();

        try {

            

            $unpaidEnrollments  = Enrollment::select('enrollment.*')
                                ->join('dance', 'enrollment.dance_id', '=', 'dance.id')
                                ->leftJoin('payments', 'enrollment.id', '=', 'payments.enrollment_id')
                                ->selectRaw('enrollment.id, enrollment.dance_id, dance.price, COALESCE(SUM(payments.amount), 0) as total_paid')
                                ->groupBy('enrollment.id', 'enrollment.user_id', 'enrollment.dance_id', 'dance.price')
                                ->havingRaw('total_paid < dance.price')
                                ->get();

            // dd($unpaidEnrollments);



            if(isset($validated['amount']) && $validated['amount'] > 0.00) {
                $payment = $validated['amount'];

                foreach($unpaidEnrollments as $unpaid) {

                    $balance = $unpaid->price - $unpaid->total_paid;

                    if($payment < $balance || $payment <= 0) {


                        if($payment > 0) {

                            Payments::create([
                                'enrollment_id' => $unpaid->id,
                                'admin_id'      => Auth::user()->id,
                                'user_id'       => $validated['id'],
                                'amount'        => $payment,
                                'date'          => Carbon::now()->toDateString(),
                            ]);

                        }
                        
                        $payment -= $balance;


                        $amount     = $validated['amount'];
                        db::commit();
                        return redirect()
                        ->route('registration')
                        ->with('status', [
                            'alert' => 'alert-success',
                            'msg'   => "Successfuly fully/partialy paid multiple fees with the amount of $amount!",
                        ]);

                    }
                    else {
            



                        Payments::create([
                            'enrollment_id' => $unpaid->id,
                            'admin_id'      => Auth::user()->id,
                            'user_id'       => $validated['id'],
                            'amount'        => $balance,
                            'date'          => Carbon::now()->toDateString(),
                        ]);

                        $payment -= $balance;

                    }


                }

                

            }



        } catch(Throwable $e) {
            db::rollBack();
            return redirect()->route('registration')->with('status', [
                'alert' => 'alert-danger',
                'msg'   => $e->getMessage(),
            ]);
        }

        $amount = $validated['amount'];
        db::commit();
        return redirect()
        ->route('registration')
        ->with('status', [
            'alert' => 'alert-success',
            'msg'   => "Client paid $amount!",
        ]);


    }

    public function delete_user(Request $request) {
        $validated = $request->validate([
            'id'    => 'required'
        ]);


        $user = User::find($validated['id']);

        if(!$user) {
            return redirect()->route('registration')->with('status', [
                'alert' => 'alert-danger',
                'msg'   => 'Invalid user',
            ]);
        }

        $user->is_active = 0;
        $user->save();

        Enrollment::where('user_id', $validated['id'])
        ->update(['is_active' => 0]);
        

        return redirect()->route('registration')->with('status', [
            'alert' => 'alert-warning',
            'msg'   => 'User deleted!',
        ]);
    }

    // ============== API ==========================


    public function get_enrollment_details(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);



        if($validator->fails()) {
            return response()
            ->json(['msg' => 'ERROR could not find the user'], 404);
        }

        $id = $request->input('id');
        

        $enrollment     = Enrollment::where('user_id', $id)
                        ->where('enrollment.is_active', 1)
                        ->join('dance', 'dance.id', '=', 'enrollment.dance_id')
                        ->first();


        $paid           = Enrollment::where('enrollment.user_id', $id)
                        ->leftJoin('payments', 'payments.enrollment_id', '=', 'enrollment.id')
                        ->sum('amount');

        $fee_amount      = Enrollment::where('enrollment.user_id', $id)
                        ->leftJoin('dance', 'dance.id', '=', 'enrollment.dance_id')
                        ->sum('price');
                        
       

        $balance        = $fee_amount - $paid;

        $sessions       = null;

        if($enrollment) {

            $sessions = DanceSession::where('enrollment_id', $enrollment->id)->get();
        }
        


        return response()->json([
            'enrollment'    => $enrollment,
            'sessions'      => $sessions,
            'balance'       => $balance,
        ]);


    }



    
}
