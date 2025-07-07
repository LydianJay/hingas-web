<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dance;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

class Registration extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $data['dances'] = Dance::all();

        $data['users']  = User::leftJoin('enrollment', 'enrollment.user_id', '=', 'users.id')
                        ->when($search, function ($query) use ($search) {
                            $query->where(function ($subquery) use ($search) {
                                $subquery->where('users.fname', 'LIKE', '%' . $search . '%')
                                        ->orWhere('users.lname', 'LIKE', '%' . $search . '%');
                            });
                        })
                        ->leftJoin('dance', 'dance.id', '=', 'enrollment.dance_id')
                        ->where('is_admin', false)
                        ->get();

        return view('pages.registration.view', $data);
    }

    public function register(Request $request) {
        $validated = $request->validate([
            'rfid'           => 'required|string',
            'email'          => 'required|email|unique:users,email',
            'fname'          => 'required|string|max:100',
            'mname'          => 'nullable|string|max:100',
            'lname'          => 'required|string|max:100',
            'gender'         => 'required|in:male,female,other',
            'dob'            => 'required|date|before:today',
            'contactno'      => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'is_active'      => 'nullable|boolean',
            'e_contact'      => 'nullable|string|max:100',
            'e_contact_no'   => 'nullable|string|max:20',
            'dance'          => 'required',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'password'       => 'required|string|min:8|confirmed', // add `password_confirmation` input if using confirmed
        ]);



        // Handle file upload
        
        DB::beginTransaction();

        try {

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos', 'public');
            }

            assert($validated['rfid'] != null && $validated['rfid'] != '' && isEmpty($validated['rfid']), 'rfid is invalid');
            // Create the user
            $user = User::create([
                'rfid'            => $validated['rfid'],
                'email'           => $validated['email'],
                'fname'           => $validated['fname'],
                'mname'           => $validated['mname'],
                'lname'           => $validated['lname'],
                'gender'          => $validated['gender'],
                'dob'             => $validated['dob'],
                'contactno'       => $validated['contactno'],
                'address'         => $validated['address'],
                'is_active'       => true,
                'e_contact'       => $validated['e_contact'],
                'e_contact_no'    => $validated['e_contact_no'],
                'photo'           => $photoPath,
                'password'        => bcrypt($validated['password']),
            ]);

            // dd($user);

            Enrollment::create([
                'dance_id'  => $validated['dance'],
                'user_id'   => $user->id,
            ]);


            DB::commit();

            return redirect()->back()->with('status', [
                'alert' => 'alert-success',
                'msg'   => 'User Enrolled!',
            ]);


        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', [
                'alert' => 'alert-danger',
                'msg'   => $e->getMessage(),
            ]);

        }

        

    }
}
