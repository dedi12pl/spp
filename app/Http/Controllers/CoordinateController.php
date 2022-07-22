<?php

namespace App\Http\Controllers;

use App\Models\Coordinate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class CoordinateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [
            'title' => 'Coordinates',
            'menu' => 'Coordinates',
            'submenu' => '',
            'deskripsi' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus accusantium laboriosam voluptates neque ut quasi, ipsum et minus molestias quos eligendi. Ab, officiis. Soluta accusantium quas quam, atque odio est!',
        ];

        if ($request->ajax()) {
            $table = Coordinate::all();


            return DataTables::of($table)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" onclick="showMap('. $row->lat. ',' . $row->long .')" data-toggle="tooltip" data-original-title="Lihat Map" id="show-map"><i class="fas fa-search-location fs-2 text-success"></i></a>
                    <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit"><i class="far fa-edit fs-2 text-info"></i></a>
                    <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="delete"><i class="far fa-trash-alt fs-2 text-danger"></i></a>';

                    return $btn;
                })
                ->make(true);
        }

        return view('coordinates.coordinates', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tower_name' => 'required|string|max:255',
                'tower_owner' => 'required',
                'lat' => 'required',
                'long' => 'required',
            ],
            [
                'tower_name.required' => 'Nama tower harus diisi!',
                'tower_owner.required' => 'Tower owner harus diisi!',
                'lat.required' => 'Latitude harus diisi!',
                'long.required' => 'Longitude harus diisi!',
                'lat.regex' => 'Format Latitude tidak valid!',
                'long.regex' => 'Format Longitude tidak valid!',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ]);
        } else {

            if($request->id){
                $coordinate = Coordinate::findOrFail($request->id);
                $tower_code = $coordinate->tower_code;

                if($request->hasFile('tower_image')){
                    $fileName = 'tower/' . time() . '.' . request()->tower_image->getClientOriginalExtension();

                    if($coordinate->tower_image != 'tower/default.png'){
                        File::delete(public_path('storage/'). $coordinate->tower_image);
                    }
                    
                    request()->tower_image->move(public_path('storage/tower/'), $fileName);
        
                    $new_tower_image = $fileName;  
                } else {
                    $new_tower_image = $coordinate->tower_image;
                }
            } else {
                $tower_code = $this->generateTowerCode();

                if($request->hasFile('tower_image')){
                    $fileName = 'tower/' . time() . '.' . request()->tower_image->getClientOriginalExtension();
                    request()->tower_image->move(public_path('storage/tower/'), $fileName);
        
                    $new_tower_image = $fileName;  
                } else {
                    $new_tower_image = 'tower/default.png';
                }
            }

            $save = Coordinate::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                [
                    'tower_code' => $tower_code,
                    'tower_name' => ucwords($request->tower_name),
                    'lat' => $request->lat,
                    'long' => $request->long,
                    'tower_owner' => ucwords($request->tower_owner),
                    'tower_image' => $new_tower_image,
                ]
            );

            if ($save) {
                if ($request->id == null) {
                    $message = 'Berhasil menambahkan titik kordinat tower...';
                } else {
                    $message = 'Berhasil mengubah titik kordinat tower...';
                }
                return response()->json($response = [
                    'status' => 'success',
                    'message' => $message,
                ]);
            } else {
                return response()->json($response = [
                    'status' => 'error',
                    'message' => 'Error tidak diketahui!'
                ]);
            }
        }
    }

    public function generateTowerCode()
    {
        $prefix = 'TC';
        $tanggal = date('dmy');
        $tahun = date('Y');
        $tower_code = (int) 1;

        // hitung jumlah permohonan
        $coordinate = Coordinate::select('tower_code')->whereYear('created_at', $tahun)->orderBy('created_at', 'desc')->first();

        if ($coordinate !== NULL) {
            $tower_code = (int) substr($coordinate->tower_code, -3) + 1;
        }

        $tower_code = str_pad($tower_code,3,'0',STR_PAD_LEFT);

        $final_tower_code = $prefix . $tanggal . $tower_code;

        return $final_tower_code;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = Coordinate::findOrFail($id);
        return response()->json($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Coordinate::where('id', '=', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Titik lokasi tower berhasil dihapus.'
        ]);
    }
}
