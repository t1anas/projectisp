<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesiController extends Controller
{
    public function index() {
        if (Auth::check()) {
            return redirect('/admin');
        }
        return view('login');
    }
    function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
        'email.required' => 'Email wajib diisi',
        'password.required' => 'Password wajib diisi', ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if(Auth::attempt($infologin)){
          if(Auth::user()->role == 'admin') {
                return redirect('/admin');
            } elseif(Auth::user()->role == 'noc') {
                return redirect('/admin/noc');
            } elseif(Auth::user()->role == 'cs') {
                return redirect('/admin/cs');
            } else {
                return redirect('/admin/admin');
            }
        } else {
            return redirect('')->withErrors('user dan password salah!')->withInput();
        }
    }
public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login'); // aman kalau route GET ada
}

}
