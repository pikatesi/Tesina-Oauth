<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;


class OidcServer extends Controller
{

    protected function getServer(){
        $config = [
            'use_openid_connect' => true,
            'issuer' => 'http://localhost:8010/'
        ];

        $storage = new \OAuth2\Storage\Pdo(\App::make('db')->getPdo());
        $server = new \OAuth2\Server($storage, $config);

        $publicKey  = file_get_contents(storage_path('pubkey.pem'));
        $privateKey = file_get_contents(storage_path('privkey.pem'));
        $keyStorage = new \OAuth2\Storage\Memory(array('keys' => array(
            'public_key'  => $publicKey,
            'private_key' => $privateKey,
        )));

        $server->addStorage($keyStorage, 'public_key');

        return $server;

    }

    public function configuration(){
        $url = 'http://localhost:8010/';

        $config = [
            'issuer' => $url,
            'authorization_endpoint' => $url . 'oauth/authorize',
            'token_endpoint' => $url . 'oauth/token',
            'jwks_uri' => $url . '/.well-known/jwks.json',
            'userinfo_endpoint' => $url . 'openid/userinfo'
        ];

        return response()->json($config);
    }

    public function userinfo(Request $request){

        $server = $this->getServer();

        $bridgedRequest  = \OAuth2\HttpFoundationBridge\Request::createFromRequest($request);
        $bridgedResponse = new \OAuth2\HttpFoundationBridge\Response();

        if (!$server->verifyResourceRequest($bridgedRequest, $bridgedResponse)) {
            return $bridgedResponse;
        } else {
            $tokenData = $server->getAccessTokenData($bridgedRequest);
            
            $userInfo = [
                'id'=> $tokenData['user_id'],
                'email' => "....",
                'name' => "..."
            ];
    
            return response()->json($userInfo);
        }


        
    }

    public function jwks(){
        $url = '';


        $publicKey = file_get_contents(storage_path('pubkey.pem'));
        $data = openssl_pkey_get_public($publicKey);
        $data = openssl_pkey_get_details($data);

        $key = $data['key'];
        $modulus = $data['rsa']['n'];
        $exponent = $data['rsa']['e'];

        $keys = [
            'keys' => [
                [
                "alg" => "RS256",
                 "kty" => "RSA",
                 "use" => "sig",
                 "n" => base64_encode($modulus),
                 "e" => base64_encode($exponent),
                 "kid" => "0",
                ]
            ],
        ];

        return response()->json($keys);
    }

    public function authorizeAction(Request $request){
        $server = $this->getServer();
        
                $bridgedRequest  = \OAuth2\HttpFoundationBridge\Request::createFromRequest($request);
                $bridgedResponse = new \OAuth2\HttpFoundationBridge\Response();
        
                // validate the authorize request
                if (!$server->validateAuthorizeRequest($bridgedRequest, $bridgedResponse)) {
                    return $bridgedResponse;
                }
        
        // display an authorization form
                if (empty($_POST)) {
                    exit('
        <form method="post">
          <label>Do You Authorize TestClient?</label><br />
          <input type="submit" name="authorized" value="yes">
          <input type="submit" name="authorized" value="no">
        </form>');
                }
                
                // fake user_id
                $userId = 5;
        
                $is_authorized = ($_POST['authorized'] === 'yes');
                $server->handleAuthorizeRequest($bridgedRequest, $bridgedResponse, $is_authorized, $userId);
        
        
                return $bridgedResponse;
    }

    public function token(Request $request){

        $server = $this->getServer();

        $bridgedRequest  = \OAuth2\HttpFoundationBridge\Request::createFromRequest($request);
        $bridgedResponse = new \OAuth2\HttpFoundationBridge\Response();

        $bridgedResponse =$server->handleTokenRequest($bridgedRequest, $bridgedResponse);

        return $bridgedResponse;
    }


}
