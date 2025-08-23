<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
class AuthCtrl extends Controller
{
    public function index() {

        // // dd(bcrypt('@default_1234'));
        return view('pages.login');
    }

    public function login(Request $request) {
        $validated = $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // dd($credentials);
        //debug 

        // $user = User::where('email', $request->email)->first();

        // dd( Hash::check( $request->password, $user->password),  $user->password, bcrypt('@default_123'), $request->password);


        $user = User::where('email', $validated['email'])->first();


        if(!$user) {
            return redirect()
            ->route('login')
            ->with('status', [
                'alert' => 'alert-danger',
                'msg'   => 'User does not exist!',
            ]);
        }


        $admin  = Admin::where('user_id', $user->id)
                ->first();

        $r      = Hash::check( $validated['password'], $admin->password);



        if ($r) {
            Auth::login($admin);
            $request->session()->regenerate();
            
            
            return redirect()->route('dashboard');
        }


        return redirect()->back()->with('status', [
            'alert' => 'alert-danger',
            'msg'   => 'Invalid email or password',
        ]);
        

    }

    public function logout() {
        Auth::logout();
        session()->invalidate();
        session()->regenerate();

        return redirect()
        ->route('login')->with('status', [
            'alert' => 'alert-info',
            'msg'   => 'User Logged Out!',
        ]);
    }


   
}
