<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReviewModel;

class ReviewController extends Controller
{
    function ReviewIndex(){

        return view('Review');
    }

    function getReviewData(){
    	$result = json_encode(ReviewModel::orderBy('id','desc')->get());
    	return $result;
    }

      function getReviewDetails(Request $req){
      	$id = $req->input('id');
    	$result = json_encode(ReviewModel::where('id','=',$id)->get());
    	return $result;
    }

     function ReviewDelete(Request $req){
    	$id = $req->input('id');
     	$result = ReviewModel::where('id','=',$id)->delete();
     	if ($result==true) {
     		return 1;
     	}else{
     		return 0;
     	}    	  	
    } 

     function ReviewUpdate(Request $req){
    	$id = $req->input('id');
    	$review_name = $req->input('review_name');
    	$review_des = $req->input('review_des');
    	$review_img = $req->input('review_img');

     	$result = ReviewModel::where('id','=',$id)->update([
     		'review_name'=> $review_name,
     		'review_des'=>$review_des,
     		'review_img'=>$review_img
     	]);
     	if ($result==true) {
     		return 1;
     	}else{
     		return 0;
     	}    	  	
    }

    function ReviewAdd(Request $req){

    	$review_name = $req->input('review_name');
    	$review_des = $req->input('review_des');
    	$review_img = $req->input('review_img');

     	$result = ReviewModel::insert([
     		'review_name'=> $review_name,
     		'review_des'=> $review_des,
     		'review_img'=> $review_img
     	]);
     	if ($result==true) {
     		return 1;
     	}else{
     		return 0;
     	}    	  	
    }
}
