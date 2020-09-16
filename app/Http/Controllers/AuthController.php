<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Notifications\NewUser;

use Validator;
use Storage;
use Intervention\Image\ImageManagerStatic as Image;
use File;
use Response;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'phone' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'country' => 'required|string'

        ]);


        $user = new User([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'country' => $request->country,
            'password' => bcrypt($request->password)


        ]);
        $user->save();

        $userdetails = [

            'subject' => 'Lastminutessay Account ',

            'greeting' => 'Hi ' . $user->name,

            'body' => "You have successfully registered your account.Below are your details",

            'phone' => 'Phone:'  . $user->phone,
            'email' => 'Email:'  . $user->email,
            'password' => 'Password:' . $request->password,
            'country' => 'Country:' . $user->country,

            'thanks' => 'Thank you for joining Lastminutessay',

        ];

        $user->notify(new NewUser($userdetails));


     
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);


        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
 
    public function updatePhoneNumber(Request $request){
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'required',
            'phone' => 'required',
             'country'=>'required',
        ]);


        if ($validator->fails()) {

            $message = $validator->errors();
            return response()->json([
                'data' => $message,

            ], 400);
        }

        $user = User::where('email', $input['email'])->first();
        $user->phone = $input['phone'];
        $user->country=$input['country'];
        $user->save();
        return response()->json([
            'data' => $user,
            'message' => "Phone update successfully"

        ], 201);

    }

    public function addUserAvatar(Request $request){

        if(!$request->hasFile('avatar')) {
            return response()->json(['Upload image not found'], 400);
          }
          $file = $request->file('avatar');
          $original_filename = $file->getClientOriginalName();
          if (!$file->isValid()) {
            return response()->json(['Invalid image uploaded '], 401);
          }

          $input = $request->all();
          $validator = Validator::make($input, [
            'id' => 'required',
          ]);
      
          if ($validator->fails()) {
      
            $message = $validator->errors();
            return response()->json([
              'data' => $message,
         
            ], 400);
          } 

          $input = $request->all();
          $user_id=$input['id'];
           
          $filenamewithextension = $request->file('avatar')->getClientOriginalName();
 
          //get filename without extension
          $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
   
          //get file extension
          $extension = $request->file('avatar')->getClientOriginalExtension();
          
          $filenametostore =$user_id.$filename.'.'.$extension;
   
     
        $thumbnailpath =storage_path('app/public/avatars/thumbnails/'.$filenametostore);
     

          if(File::exists($thumbnailpath)){
           Storage::delete('avatars/thumbnails/'.$filenametostore);
           Storage::delete('avatars/'.$filenametostore);
         }

     $request->file('avatar')->storeAs('avatars',$filenametostore);
       $path =$request->file('avatar')->storeAs('avatars/thumbnails',$filenametostore);
      
    
        $img = Image::make($thumbnailpath)->resize(150, 150)->save($thumbnailpath);


      
       $user = User::where('id', $input['id'])->first();
       $user->	avatar_url =  $path;
       $user->save();


       

     
   
       return response()->json([
               'path'=>  $path,
              'message' => "Profile image updated successfully",
         
            ], 201);
       
     


    }

  public function getAvatars(Request $request)
  {
    
    $input = $request->all();
          $validator = Validator::make($input, [
            'avatar_url' => 'required',
          ]);
      
          if ($validator->fails()) {
      
            $message = $validator->errors();
            return response()->json([
              'data' => $message,
         
            ], 400);
          } 

         $input = $request->all();
          $avatar_url=$input['avatar_url'];

  //$thumbnailpath = public_path('storage/'. $avatar_url);
  
  
        $thumbnailpath =storage_path('app/public/'. $avatar_url);
     
  
    $path=  $thumbnailpath;

    if(!File::exists($path)) abort(404);

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    
    return $response;

  }



 
//Add user avatar/profile image
     /*
public function addUserAvatar(Request $request){

    if($request->hasFile('profile_image')) {
        //get filename with extension
        $input = $request->all();
        $user_id=$input['id'];

        $filenamewithextension = $request->file('profile_image')->getClientOriginalName();
 
        //get filename without extension
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
 
        //get file extension
        $extension = $request->file('profile_image')->getClientOriginalExtension();
 
        //filename to store
        $filenametostore =$user_id.'.'.$extension;
 
        //Upload File
        $request->file('profile_image')->storeAs('profile_images', $filenametostore);
        $request->file('profile_image')->storeAs('profile_images/thumbnail', $filenametostore);
 
        //Resize image here
        $thumbnailpath = public_path('storage/profile_images/thumbnail/'.$filenametostore);
        $img = Image::make($thumbnailpath)->resize(150, 150)->save($thumbnailpath);

        $user = User::where('id', $input['id'])->first();
        $user->	avatar_url = $thumbnailpath;
        $user->save();

        return response()->json([
            'path' => $thumbnailpath,
            'message' => "Profile avatar added successfully"

        ], 201);

       //  $path = storage_path() . '/profile_images/thumbnail/' . $filename;
         
         //  $path = storage_path() . 'public/profile_images/thumbnail/'.$filenametostore;
      //  $path = public_path('profile_images/thumbnail/'.$filenametostore);

  if(!File::exists($path)) abort(404);
 
            $file = File::get($path);
            $type = File::mimeType($path);
        
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            
            return $response;
    


        
   
   
 
    }



}  
//end add user method

public function getFile($path,$file){
    if($file){
        $url = $path."/".$file;
    }else{
        $url = $path;
    }
    $path = storage_path("app/") . $url;

    if(!File::exists($path)) abort(404);

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
}
*/
}
