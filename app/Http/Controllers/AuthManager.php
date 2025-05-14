<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthManager extends Controller
{
    //
    function login(){
        return view('auth.login');
    }

    function loginPost(Request $request){ //handle login
        $request->validate([
            'matricnum' => 'required',
            'password' => 'required'
        ]);
        $credentials = $request->only('matricnum', 'password');
        if(Auth::attempt($credentials)){
            return redirect()->intended(route("home"));
        }
        return redirect("login")->with("error", "Invalid Matric Number or Password");
    }

    function logout(){
        Auth::logout();
        return redirect("login");
    }

    function register(){
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'matricnum' => 'required|string|max:255',
            'siswa_email' => [
                'required',
                'email',
                'unique:users,siswa_email', // Ensure the email is unique in the users table
                'regex:/^[a-zA-Z0-9._%+-]+@siswa\.ukm\.edu\.my$/', // Ensure email ends with @siswa.ukm.edu.my
            ],
            'password' => 'required|min:5|max:12|confirmed', // Add password confirmation
        ]);
    
        $user = new User(); // Create a new user instance
        $user->name = $request->name;
        $user->matricnum = $request->matricnum;
        $user->siswa_email = $request->siswa_email; // Store the validated email
        $user->password = Hash::make($request->password); // Hash the password
    
        if ($user->save()) {
            return redirect()->route('login')->with("success", "Registration Successful");
        }
    
        return redirect("register")->with("error", "Registration Failed");
    }
    
    
}
