<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Grade;

class GradoController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllActive()
    {
        $grados = Grade::where('status',1)->orderBy('id')->get();

        return response()->json([
            'data' => $grados
        ]);
    }

}
