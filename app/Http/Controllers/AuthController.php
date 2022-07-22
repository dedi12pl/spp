<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Login | Tower Map Kominfo-SP',
            'menu' => 'Login',
            'submenu' => '',
            'deskripsi' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus accusantium laboriosam voluptates neque ut quasi, ipsum et minus molestias quos eligendi. Ab, officiis. Soluta accusantium quas quam, atque odio est!',
        ];

        return view('auth.login', $data);
    }

    public function authenticate(Request $request)
    {
        $credentials = Validator::make(
            $request->all(),
            [
                'email' => 'required',
                'password' => 'required|min:4',
            ],
            [
                'email.required' => 'Email harus diisi!',
                'password.required' => 'Password harus diisi!',
                'password.min' => 'Password minimal 8 karakter!',
            ]
        );

        if ($credentials->fails()) {
            return redirect('/')
                ->withErrors($credentials)
                ->withInput();
        }

        $credentials = request()->validate([
            'email' => 'required',
            'password' => 'required|min:4',
        ]);


        User::where('email', $request->email)->update([
            'last_login' => Carbon::now(),
            'ip_address' => request()->ip()
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('successLogin', 'Anda berhasil login');
        }

        return back()->with('loginError', 'Email atau password anda salah, silahkan masukkan email dan
                            password dengan benar.')->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();

        return response()->json($response = [
            'status' => 'success',
            'message' => 'Andaa telah logout!'
        ]);
    }

    public function quick(Request $request)
    {
        $user =  User::where('instansi_id', $request->id)->first();

        if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2) {
            $user = Auth::loginUsingId($user->id);

            if ($user) {
                User::where('id', $user->id)->update([
                    'last_login' => Carbon::now(),
                    'ip_address' => request()->ip()
                ]);

                $request->session()->regenerate();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Anda telah login sebagai ' . $user->name,
                ]);
            } else {
                $request->session()->regenerate();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Login gagal!'
                ]);
            }
        } else {
            $request->session()->regenerate();
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses!'
            ]);
        }
    }
}
