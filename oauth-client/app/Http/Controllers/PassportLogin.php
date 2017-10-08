<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;

class PassportLogin extends Controller
{

    private function getConfig(){
      return [
        'client_id'     => ENV('PASSPORT_CLIENT_ID'),
        'client_secret' => ENV('PASSPORT_CLIENT_SECRET'),
        'redirect_uri'  => ENV('PASSPORT_CLIENT_REDIRECT'),
        'authorize_uri' => ENV('PASSPORT_HOST') . 'oauth/authorize',
        'token_uri'     => ENV('PASSPORT_HOST') . 'oauth/token',
        'user_api'      => ENV('PASSPORT_HOST') . 'api/user',
        'sample_api'    => ENV('PASSPORT_HOST') . 'api/wow',
      ];
    }

    public function redirectToAuthServer(){
      $config = $this->getConfig(); 

      $query = http_build_query([
        'client_id' => $config['client_id'],        
        'redirect_uri' => $config['redirect_uri'],
        'response_type' => 'code',
        //'scope' => 'show-time',
      ]);

      Log::info($config);
      Log::info($query);
      return redirect($config['authorize_uri']. '?' . $query);
    }


    public function callbackEndpoint(Request $request){
      try{    
        $http = new \GuzzleHttp\Client;
        
        $config = $this->getConfig(); 
        Log::info($config);
        Log::info([
            'grant_type' => 'authorization_code',
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'redirect_uri' => $config['redirect_uri'],
            'code' => $request->query('code'),
        ]);
        $response = $http->post($config['token_uri'], [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => $config['client_id'],
                'client_secret' => $config['client_secret'],
                'redirect_uri' => $config['redirect_uri'],
                'code' => $request->query('code'),
            ],
        ]);
    



        $responseData = json_decode((string) $response->getBody(), true); 

        Session::put('passport-token',$responseData);

        $response = $http->get($config['user_api'], [
          'headers' => [
            'Authorization' => 'Bearer ' . $responseData['access_token']
          ]
        ]);

        Session::put('passport-response',json_decode((string) $response->getBody(), true));
      
      }catch(\Exception $ex){
          session()->flash('error', $ex->getMessage());    
          Log::error($ex);
          
      }
      return redirect('/');
  }

   
}
