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
        $search     = $request->input('search');
        $page       = $request->input('page', 1);
        $perPage    = 12;


        $data['dances'] = Dance::all();

        $data['users']  = User::leftJoin('admin', 'admin.id', '=', 'users.id')
                        ->when($search, function ($query) use ($search) {
                            $query->where(function ($subquery) use ($search) {
                                $subquery->where('users.fname', 'LIKE', '%' . $search . '%')
                                        ->orWhere('users.lname', 'LIKE', '%' . $search . '%');
                            });
                        })
                        ->whereNull('admin.user_id')
                        ->select('users.*')
                        ->get();

        
        $data['page']           = $page;
        $data['perPage']        = $perPage;
        $data['count']          = User::leftJoin('admin', 'admin.id', '=', 'users.id')
                                ->whereNull('admin.user_id')
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
                'rfid'            => $validated['rfid'],
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
                        ->back()
                        ->with('status', [
                    'alert' => 'alert-danger',
                    'msg'   => 'Invalid user!',
                ]);
            }


            $user->update([
                'rfid'            => $validated['rfid'],
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

            return redirect()->back()->with('status', [
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
}
