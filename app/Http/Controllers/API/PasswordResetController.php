<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NewPassword;
use App\Notifications\NewPin;
use App\PasswordReset;
use Validator;
use App\User;

use Notification;



class PasswordResetController extends Controller
{
    //

    public function sendPin(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'required',
            'pin' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {

            $message = $validator->errors();


            return response()->json([
                'data' => $message,

            ], 400);
        }

        $user = User::where('email', $input['email'])->first();

        if ($user != null) {

            $find = PasswordReset::where('email', $input['email'])->first();
            if ($find != null) {

                $pass = PasswordReset::where('email', $input['email'])->first();
                $pass->pin = $input['pin'];
                $pass->save();
            } else {

                PasswordReset::create($input);
            }
            $pin = PasswordReset::first();

            $details = [

                'subject' => 'Forget Password verification code',

                'greeting' => 'Hi ' . $user->name,

                'body' => 'Enter below code in your Lastminutessay App to reset password.This code is valid for next 24 hour.',

                'thanks' => 'Thank you for using Lastminutessay academic services',

                'actionText' => $input['pin'],

                'actionURL' => url('#'),



            ];

            $user->notify(new NewPin($details));






            return response()->json([
                'data' => $user,
                'message' => "Pin reset code sent"

            ], 201);
        } else {
            return response()->json([
                'data' => "null",
                'message' => "Email not found"

            ], 404);
        }
    }


    public function validatePin(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'password' => 'required',
            'pin' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {

            $message = $validator->errors();
            return response()->json([
                'data' => $message,

            ], 400);
        }

        $result = PasswordReset::where('email', $input['email'],)
            ->where('pin', $input['pin'])->first();

        if ($result != null) {
            $user = User::where('email', $input['email'])->first();
            $user->password = bcrypt($input['password']);
            $user->save();

            $details = [

                'subject' => 'Password Update Successful',

                'greeting' => 'Hi ' . $user->name,

                'body' => 'You have successfully changed your password.',

                'thanks' => 'Thank you for using Lastminutessay  academic services',

            ];

            $user->notify(new NewPassword($details));

            return response()->json([
                'data' => $user,
                'message' => "Password update successfully"

            ], 201);
        } else {
            return response()->json([
                'data' => $result,
                'message' => "Pin code not  found"

            ], 404);
        }
    }

    //Resetting password when logged in
    public function resetPasswordUserLoggedIn(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {

            $message = $validator->errors();
            return response()->json([
                'data' => $message,

            ], 400);
        }

        $user = User::where('email', $input['email'])->first();
        $user->password = bcrypt($input['password']);
        $user->save();

        $details = [

            'subject' => 'Password Update Successful',

            'greeting' => 'Hi ' . $user->name,

            'body' => 'You have successfully changed your password.',

            'thanks' => 'Thank you for using Lastminutessay  academic services',

        ];

        $user->notify(new NewPassword($details));

        return response()->json([
            'data' => $user,
            'message' => "Password update successfully"

        ], 201);
    }

    //End reset password



}
