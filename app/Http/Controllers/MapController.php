<?php

namespace App\Http\Controllers;

use App\Models\Coordinate;
use App\Http\Resources\TowerResource;

class MapController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Maps',
            'menu' => 'Maps',
            'submenu' => '',
            'deskripsi' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus accusantium laboriosam voluptates neque ut quasi, ipsum et minus molestias quos eligendi. Ab, officiis. Soluta accusantium quas quam, atque odio est!',
        ];

        return view('map.map', $data);
    }

    public function map()
    {
        $tower = Coordinate::all();

        foreach ($tower as $u) {
            $data[] = [
                'tower_code' => $u->tower_code,
                'tower_name' => $u->tower_name,
                'tower_owner' => $u->tower_owner,
                'lat' => $u->lat,
                'long' => $u->long,
                'tower_image' => $u->tower_image,
            ];
        }

        return response()->json([
            'status'     => 'success',
            'data' => $data,
        ]);
    }

    public function googleMap()
    {
        $data = [
            'title' => 'Map',
            'menu' => 'Map',
            'submenu' => '',
            'deskripsi' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus accusantium laboriosam voluptates neque ut quasi, ipsum et minus molestias quos eligendi. Ab, officiis. Soluta accusantium quas quam, atque odio est!',
        ];

        $coordinates = Coordinate::all();

        foreach ($coordinates as $u) {
            $tower[] = [
                'tower_name' => $u->tower_name,
                'lat' => $u->lat,
                'long' => $u->long,
            ];
        }

        $tower = json_encode($tower);

        return view('map.google-map', $data, compact('tower'));
    }


    public function cor()
    {
        $data = [
            'title' => 'Map',
            'menu' => 'Map',
            'submenu' => '',
            'deskripsi' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus accusantium laboriosam voluptates neque ut quasi, ipsum et minus molestias quos eligendi. Ab, officiis. Soluta accusantium quas quam, atque odio est!',
        ];

        return view('coordinates.cor', $data);
    }
}
