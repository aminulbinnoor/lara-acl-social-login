<?php
 namespace App\Http\Controllers;
 use Illuminate\Http\Request;
 use Validator,Redirect,Response,File,Hash;
 use Socialite;
 use App\Models\User;
 use Auth;

 class FacebookController extends Controller
 {

  public function redirect()
  {
     return Socialite::driver('facebook')->redirect();
  }

 public function callback()
 {
    try {

        $user = Socialite::driver('facebook')->user();
        $credentials = User::where('facebook_id',$user->getId())->first();
        if($credentials){
          Auth::loginUsingId($credentials->id);
          return redirect()->route('home');
        }
        $create['name'] = $user->getName();
        $create['email'] = $user->getEmail();
        $create['facebook_id'] = $user->getId();
        $create['password'] = Hash::make('12345678');
        $createdUser = User::create($create);
        Auth::loginUsingId($createdUser->id);

        return redirect()->route('home');

    } catch (\Exception $e) {
        return redirect('redirect');
    }
  }

 }
