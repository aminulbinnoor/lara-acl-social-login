<?php
 namespace App\Http\Controllers;
 use Illuminate\Http\Request;
 use Validator,Redirect,Response,File,Hash;
 use Socialite;
 use Auth;
 use App\Models\User;

 class GoogleController extends Controller
 {

  public function redirect()
  {
     return Socialite::driver('google')->redirect();
  }

 public function callback()
 {
   try {
        $googleUser = Socialite::driver('google')->user();
        $existUser = User::where('google_id',$googleUser->id)->first();
        if($existUser) {
            Auth::loginUsingId($existUser->id);
        }
        else {
            $user = new User;
            $user->name = $googleUser->name;
            $user->email = $googleUser->email;
            $user->google_id = $googleUser->id;
            $user->password = md5(rand(1,10000));
            $user->save();
            Auth::loginUsingId($user->id);
        }
        return redirect()->to('/home');
    }
    catch (Exception $e) {
        return 'error';
    }
  }

 }
