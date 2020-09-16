<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrderMaterial;
use Validator;
use Storage;
use Response;
use Illuminate\Support\Facades\DB;


class OrderMaterialsController extends Controller
{


  //Start upload files

  public function uploadfiles(Request $request)
  {
    if (!$request->hasFile('data')) {
      return response()->json(['upload_file_not_found'], 400);
    }
    $file = $request->file('data');

    $original_filename = $file->getClientOriginalName();
    if (!$file->isValid()) {
      return response()->json(['invalid_file_upload'], 401);
    }


    $path = $request->file('data')->store('ordermaterials');



    $filename = basename($path);

    // $path = public_path() . "/ordermaterials";


    // $->move($path, $file->getClientOriginalName());

    //   $path = $path . "/ " . $file->getClientOriginalName();



    $ext = $file->getClientOriginalExtension();
    $input = $request->all();
    $order_id = $input['order_id'];
       $email = $input['email'];


  $orderfile=  OrderMaterial::create(
      [


        'order_id' => $order_id,
        'filename' => $filename,
        'original_filename' => $original_filename,

        'email' =>  $email,
      ]
    );

    return response()->json([
        'data' =>$orderfile,
      ], 201);


 
  }

  //End upload files
  //Delee files


  public function deletefiles(Request $request, $id)
  {

    $input = $request->all();
    $validator = Validator::make($input, [
      'file_name' => 'required',
    ]);

    if ($validator->fails()) {

      $message = $validator->errors();
      return response()->json([
        'data' => $message,
        'message' => 'File not deleted'
      ], 404);
    } else {
      $ordermaterial = OrderMaterial::findOrFail($id);
      $ordermaterial->delete();

      //OrderMaterial::delete($id);
      $filename = $input['file_name'];
      Storage::delete('ordermaterials/' . $filename);

      return response()->json([
        'data' => 'File deleted successfully'
      ], 422);
    }
  }

public function vieworderfiles($id){
/* 
$files = DB::table('order_materials')->where('order_id', '=', $id)
    ->orderBy('id', 'desc')
    ->get(); 

    */

    $files=OrderMaterial::where('order_id', '=', $id)->get();


if ($files->isEmpty()) {
      return response()->json([
        'data' => $files,
        'message' => 'No files found for this order.'
      ], 404);
    }
	else{

	return response()->json([
      'data' => $files,
      'message' => 'Files retrieved successfully'
    ], 200);

  }




}


public function viewUserOrderfiles($email){
/* 
$files = DB::table('order_materials')->where('order_id', '=', $id)
    ->orderBy('id', 'desc')
    ->get(); 

    */

    $files=OrderMaterial::where('email', '=', $email)->get();


if ($files->isEmpty()) {
      return response()->json([
        'data' => $files,
        'message' => 'No files found for this order.'
      ], 404);
    }
	else{

	return response()->json([
      'data' => $files,
      'message' => 'Files retrieved successfully'
    ], 200);

  }




}




public function downloadorderfiles($filename){

//return Storage::get('ordermaterials/'.$filename);


//return Storage::get('ordermaterials/'.$filename);




$file = Storage::get('ordermaterials/'.$filename);
$mimetype = Storage::mimeType('ordermaterials/'.$filename);


   $response = Response::make($file, 200);
   $response->header('Content-Type', $mimetype);
   return $response;



}




}
