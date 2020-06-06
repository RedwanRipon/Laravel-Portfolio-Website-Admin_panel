<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CoursesModel;
class CoursesController extends Controller
{
    function CoursePage(){

        $CoursesData = json_decode(CoursesModel::orderBy('id','desc')->get());
        return view('Course',['CoursesData'=>$CoursesData]);
    }
}
