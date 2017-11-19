<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;


class Oidc extends Controller
{

    protected function getClient(){
        $oidc = new \OpenIDConnectClient(env('OIDC_URL'),
            env('OIDC_CLIENT_ID'), env('OIDC_CLIENT_SECRET'));

        $oidc->addScope(['profile', 'openid']);
        $oidc->setRedirectURL(env('LOCAL_URL') . 'login/oidc/oauth0/redirect/');

        return $oidc;
    }

    public function login(){
        try{
            $this->getClient()->authenticate();
        }catch(\Exception $ex){
            session()->flash('error in autenticating', $ex->getMessage());
            return redirect('/');
        }
    }

    public function redirect(){
        try{
            $oidc = $this->getClient();
            $oidc->authenticate();


            $data = [
                'access-token' => $oidc->getAccessToken(),
                'refresh-token' => $oidc->getRefreshToken(),
                'user-info' => $oidc->requestUserInfo(),
                'response' => $oidc->getTokenResponse(),
                'jwt' => $this->jwtDecode($oidc->getIdToken()),
            ];

            //return response()->json($data);
            Session::put('userInfo', $data);
        }catch(\Exception $ex){
            session()->flash('error', $ex->getMessage());
            //return response()->json($ex);
        }
        return redirect('/');
    }


// ----------    DISCOVERY -------------------

    public function discovery(){
        try{
            $oidc = new \OpenIDConnectClient(env('OIDC_URL'));
            $oidc->setClientName('test app');
            $oidc->setRedirectURL(env('LOCAL_URL') . 'login/oidc/oauth0/redirect/');
            $oidc->register();
            

            $provider_data = [
                'client_id' => $oidc->getClientID(),
                'client_secret' => $oidc->getClientSecret(),
                'discoveryInfo' => $this->getValue($oidc, 'wellKnown')
            ];

            Session::put('discoveryInfo', $provider_data);
        }catch(\Exception $ex){
            session()->flash('error', $ex->getMessage());            
        }
        return redirect('/');
    }

    // ------------- utils

    private function getValue($object, $property){
        $prop =  (new \ReflectionClass(get_class($object)))->getProperty($property);
        $prop->setAccessible(true);
        return $prop->getValue($object);
    }

    private function jwtDecode($jwt){
        $section = explode(".", $jwt);
        $jwt = [
            'header' => json_decode(base64url_decode($section[0])),
            'body' => json_decode(base64url_decode($section[1]))
            ];
        return $jwt;
    }
}
