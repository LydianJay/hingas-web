<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AuthCtrl extends Controller
{
    public function index() {

        // // dd(bcrypt('@default_1234'));
        return view('pages.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // dd($credentials);
        //debug 

        // $user = User::where('email', $request->email)->first();

        // dd( Hash::check( $request->password, $user->password),  $user->password, bcrypt('@default_123'), $request->password);





        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            
            return redirect()->route('dashboard');
        }


        return redirect()->back()->with('status', [
            'alert' => 'alert-danger',
            'msg' => 'Invalid email or password',
        ]);
        

    }

    public function logout() {
        Auth::logout();
        session()->invalidate();
        session()->regenerate();

        return redirect()->route('home');
    }


   
}
