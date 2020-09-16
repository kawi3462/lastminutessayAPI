<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReferrals;
use App\Referral;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Notifications\NewReferral;

class ReferralController extends Controller
{

public function store(StoreReferrals $request){

    $validatedData=$request->validate();
    $validatedData = $request->all();


$user=Auth::user();

$referral=new Referral();
$referral->name=$validatedData['name'];
$referral->email=$validatedData['email'];
$referral->country=$validatedData['country'];
$referral->phone=$validatedData['phone'];




if($user->referrals()->save($referral))
{

  $details = [

                'subject' => 'Friend Referral Successful',

                'greeting' => 'Hi ' . $user->name,
                   'body' => 'You have successfully referred your friend to use our service.Make sure you 
                   share with him or her our application link or website.Once they sign up and place order
                   we will credit your wallet.Below are your friend details.',
                   'name' => 'Name:' . $referral->name,
                   'email' => 'Email:'  . $referral->email,
                   'phone' => 'Phone:'  . $referral->phone,
                   'country' => 'Country:' . $user->country,


                'thanks' => 'Thank you so much for using Lastminutessay  academic writing services and referring your friend',

            ];

            $user->notify(new NewReferral($details));






return response()->json([
    'data'=>$referral,

],201);

}
else{
return response()->json([
    'data'=>$user,

],500);
    
}

}

}

