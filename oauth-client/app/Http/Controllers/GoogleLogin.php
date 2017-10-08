<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;


use Socialite;


class GoogleLogin extends Controller
{
    public function redirectToAuthServer(){
        return Socialite::driver('google')->redirect();

        return Socialite::driver('google')
                ->scopes(['https://www.googleapis.com/auth/contacts.readonly'])
                ->redirect();
    }

    public function callbackEndpoint(){


        try{                   
            $user = Socialite::driver('google')->user();

            
            Session::put('google',$user);
            Log::info('google ok');


            
            
            Log::info(json_encode($user));
/*
            $userAuth = new \App\User([
                'name'     => $user->name,
                'email'    => $user->email,
                //'provider' => $provider,
                'provider_id' => $user->id
            ]);
            
            Auth::login($userAuth,true);
            */
        }catch(\Exception $ex){
            session()->flash('error', $ex->getMessage());            
        }
        return redirect('/');
        
    }
}
