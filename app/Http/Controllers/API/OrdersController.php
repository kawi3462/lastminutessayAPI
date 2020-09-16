<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use App\Orders;
use App\Http\Controllers\Controller as Controller;
use App\OrderMaterial;
use Illuminate\Support\Facades\DB;
use Storage;
use App\Notifications\NewOrder;
use Illuminate\Support\Facades\Auth;


class OrdersController extends Controller
{

  public function index()
  {
    $orders = Orders::all();
    $message = "Order retrieved successfully";
    $status = true;

    $response = $this->response($status, $orders, $message);
    return $response;
  }
  public function insert(Request $request)
  {
    $input = $request->all();

    $user=Auth::user();

    $validator = Validator::make($input, [
      'email' => 'required',
      'topic' => 'required',
      'subject' => 'required',

      'pages' => 'required',
      'style' => 'required',
      'document' => 'required',
      'academiclevel' => 'required',
      'langstyle' => 'required',
      'urgency' => 'required',
      'spacing' => 'required',
      'total' => 'required',
      'description' => 'required',
      'status' => 'required',
      'payment' => 'required',
      'created_at' => 'required',

    ]);

    if ($validator->fails()) {
      $order = $input;
      $message = $validator->errors();
      $status = false;

      return response()->json([
        'data' => $message,
        'message' => 'Order not submitted'
      ], 422);
    }
    $order = Orders::create($input);
    $message = 'Order submitted successfully.';
    $status = true;
    $lastInsertedId=$order->id;
//
       
    
  $details = [

                'subject' => 'Order # '.$lastInsertedId.' Details',

                   'greeting' => 'Hello ' . $user->name,
                   'body' => 'We received your order.Below are brief details of your order.',
                   'id' => 'Order#:' . $lastInsertedId, 
                   'topic' => 'Topic:'  .$order->topic,
                   'subjectorder' => 'Subject:'  . $order->subject,
                   'urgency' => 'Urgency:' . $order->urgency,
                   'document' => 'Document Type:' . $order->document,
                   'pages' => 'Number of pages/Words:' . $order->pages,
                   'total' => 'Total Cost:' . $order->total,
                   'thanks' => 'Thank you  for submitting your order.We will do our best to surpass your expectations',
                   'regards' =>'Regards',
                   'company'=>'Lastminutessay.us Team',

            ];

            $user->notify(new NewOrder($details));



    return response()->json([
      'id' =>$lastInsertedId, 
      'email'=>$order->email,
       'subject'=>$order->subject,
  'urgency'=>$order->urgency,
'document'=>$order->document,
'pages'=>$order->pages
   
    ], 201);



    //$response=$this->response($status,$order,$message);
    //return $response;




  }

  public function update(Request $request, $id)
  {
    $input = $request->all();

    $validator = Validator::make(
      $input,
      [
        'description' => 'required',
        'langstyle' => 'required',
        'topic' => 'required',
        'style' => 'required',

      ]
    );

    if ($validator->fails()) {
      $order = $input;
      $message = $validator->errors();
      return response()->json([
        'data' => $message,
        'message' => 'Order not updated'
      ], 422);
    }
    //Update orders
 $status=Orders::find($id)->update(
      [
        'description' => $input['description'],
        'langstyle' => $input['langstyle'],
        'topic' => $input['topic'],
        'style' => $input['style'],
      ]
      );
      if($status){

$order= Orders::find($id);
 return response()->json([
      'data' =>$order,
      'message' => 'Order updated successfully'
    ], 200);
      }
      else{
  return response()->json([
       
        'message' => 'Order not updated'
      ], 422);

      }




  }

  public function show($id)
  {
    $order = Orders::find($id);

    if (is_null($order)) {
      return response()->json([
        'data' => $order,
        'message' => 'Order not found.'
      ], 404);
    }
    return response()->json([
      'data' => $order,
      'message' => 'Order retrieved successfully'
    ], 200);
  }

  public function delete($id)
  {
    $order = Orders::findorFail($id);
   $order->delete();

     $ordermaterial = OrderMaterial::where('order_id', '=', $id)->get();
     //dd($ordermaterial);
    

    

     if($ordermaterial!=null){
    // $ordermaterial->delete();
       foreach($ordermaterial as $file){
         $filename=$file->filename;

           Storage::delete('ordermaterials/' . $filename);

          $file->delete();

       }


     }
    

    return response()->json([
     // 'data' => $order,
      'message' => 'Order deleted successfull'
    ], 200);

    
  }

// Public function to show user orders

public function myuserorders($email)
{


$orders = DB::table('orders')->where('email', '=', $email)
    ->orderBy('id', 'desc')
    ->get(); 


  if (is_null($orders)) {
      return response()->json([
        'data' => $email,
        'message' => 'No Orders  found for this user.'
      ], 404);
    }
	
	return response()->json([
      'data' => $orders,
      'message' => 'Orders retrieved successfully'
    ], 200);
	

}
 


  /*public function response ($status,$orders,$message)
  {
  $return['success']=$status;
  $return['data'] = $orders;
        $return['message'] = $message;
        return $return; 
  } 

public function sendError($status,$orders,$message)
{
 $return['fail']=$status;
  $return['data'] = $orders;
        $return['message'] = $message;
        return $return; 



$orders = DB::table('orders')->where('email', '=', $email)
    ->orderBy('id', 'desc')
    ->get(); 



} */
}
