<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;


class OidcLocalServer extends Controller
{

    protected function getClient(){
        $oidc = new \OpenIDConnectClient('http://localhost:8010/',
            'testclient',
            'testpass');

        $oidc->addScope(['openid']);
        $oidc->setRedirectURL('http://localhost:8000/login/oidc/local/redirect/');


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

            $jwt = $oidc->getIdToken();
            if ($jwt) $jwt = $this->jwtDecode($oidc->getIdToken());

            $data = [
                'access-token' => $oidc->getAccessToken(),
                'refresh-token' => $oidc->getRefreshToken(),
                'user-info' => $oidc->requestUserInfo(),
                'response' => $oidc->getTokenResponse(),
                'jwt' => $jwt,
            ];

            Session::put('userInfo', $data);
        }catch(\Exception $ex){
            session()->flash('error', $ex->getMessage());
        }
        return redirect('/');
    }


// ----------    DISCOVERY -------------------

    public function discovery(){
        try{
            $oidc = new \OpenIDConnectClient("https://test-tesi.eu.auth0.com/");
            $oidc->setClientName('test app');
            $oidc->setRedirectURL('http://localhost:8000/login/oidc/oauth0/redirect/');
            $oidc->register();
            

            $provider_data = [
                'client_id' => $oidc->getClientID(),
                'client_secret' => $oidc->getClientSecret(),
                'discoveryInfo' => $this->getValue($oidc, 'wellKnown')
            ];

            Session::put('discoveryInfo', $provider_data);
        }catch(\Exception $ex){
            session()->flash('error', $ex->getMessage() );
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
