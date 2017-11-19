<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use InoOicClient\Flow\AbstractFlow;


class OicClient extends Controller
{

    protected function getClient(){

        $opUrl = env('OIDC_URL');

        $config = [
            'client_info' => [
                'client_id' => env('OIDC_CLIENT_ID'),
                'redirect_uri' => 'http://localhost:8000/login/occ/oauth0/redirect/',

                'authorization_endpoint' => $opUrl . 'authorize',
                'token_endpoint' => $opUrl .'oauth/token',
                'user_info_endpoint' => $opUrl . 'userinfo',

                'authentication_info' => [
                    'method' => 'client_secret_post',
                    'params' => [
                        'client_secret' => env('OIDC_CLIENT_SECRET')
                    ]
                ],

            ]
        ];

        $client = new \InoOicClient\Flow\Basic($config);



        $client->getHttpClientFactory()
            ->setDefaultOptions(['curloptions' => []]);

        return $client;
    }

    public function login(){
        try{
            $scopes = 'openid profile';

            $url = $this->getClient()
                ->getAuthorizationRequestUri($scopes);

            //echo $url;
            return redirect($url);
        }catch(\Exception $ex){
            session()->flash('error', $ex->getMessage());

            return redirect('/');
        }
    }

    public function redirect(){
        try{
            $userInfo = $this->getClient()->process();

            $data = [
                //'access-token' => $oidc->getAccessToken(),
                //'refresh-token' => $oidc->getRefreshToken(),
                'user-info' => $userInfo,
                //'response' => $oidc->getTokenResponse(),
                //'jwt' => $this->jwtDecode($oidc->getIdToken()),
            ];

            //return response()->json($data);
            Session::put('userInfo2', $data);
        }catch(\Exception $ex){
            throw $ex;
            session()->flash('error', $ex->getMessage());
            //return response()->json($ex);
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
