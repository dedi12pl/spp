<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
         $data = [
            'title' => 'Dashboard',
            'menu' => 'Dashboard',
            'submenu' => '',
            'deskripsi' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus accusantium laboriosam voluptates neque ut quasi, ipsum et minus molestias quos eligendi. Ab, officiis. Soluta accusantium quas quam, atque odio est!',
        ];

        return view('dashboard.index', $data);
    }
}
