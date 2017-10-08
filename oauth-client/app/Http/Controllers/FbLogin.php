<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class FbLogin extends Controller
{

    private function getFacebookClient(){
      return new \Facebook\Facebook([
        'app_id' => env('FB_CLIENT_ID'),
        'app_secret' => env('FB_CLIENT_SECRET'),
        'default_graph_version' => 'v2.10',
        'persistent_data_handler' => new MyLaravelPersistentDataHandler(),
        //'default_access_token' => '{access-token}', // optional
      ]);
    }

    public function redirectToAuthServer(){
        $fb = $this->getFacebookClient();
          
        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email','public_profile','user_about_me','user_location']; // Optional permissions
        $loginUrl = $helper->getLoginUrl(env('FB_CLIENT_REDIRECT'), $permissions);

        return redirect($loginUrl);        
    }


    public function callbackEndpoint(){
      try{    
        $fb = $this->getFacebookClient();
        
        $helper = $fb->getRedirectLoginHelper();

        $accessToken = $helper->getAccessToken();
        
        if (!isset($accessToken)) {          
          session()->flash('error ', 'FB access error: ' . $helper->getError() . '-' . $helper->getErrorDescription());                                      
        } else {
          
          $oAuth2Client = $fb->getOAuth2Client();
          $tokenMetadata = $oAuth2Client->debugToken($accessToken);

          Session::put('fb-token-metadata',$tokenMetadata);

          Session::put('fb-token',$accessToken);
          
          $me = $fb->get('/me?fields=name,email,about',$accessToken);
                  
          Session::put('fb-user',$me->getDecodedBody());
        }
        
        
        
/*
        echo '<h3>Envelop</h3>';
        var_dump($accessToken);

        // Logged in
        echo '<h3>Access Token</h3>';
        var_dump($accessToken->getValue());
        
        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();
        
        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        echo '<h3>Metadata</h3>';
        var_dump($tokenMetadata);
        
        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId('297674857307164'); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();
*/
      }catch(\Exception $ex){
          session()->flash('error', $ex->getMessage());    
          
      }
      return redirect('/');
  }

    public function callbackEndpointComplete(){
        try{    
          $fb = $this->getFacebookClient();
          
          $helper = $fb->getRedirectLoginHelper();
          
          try {
            $accessToken = $helper->getAccessToken();
          } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }
          
          if (! isset($accessToken)) {
            if ($helper->getError()) {
              header('HTTP/1.0 401 Unauthorized');
              echo "Error: " . $helper->getError() . "\n";
              echo "Error Code: " . $helper->getErrorCode() . "\n";
              echo "Error Reason: " . $helper->getErrorReason() . "\n";
              echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
              header('HTTP/1.0 400 Bad Request');
              echo 'Bad request';
            }
            exit;
          }
          
          // Logged in
          echo '<h3>Access Token</h3>';
          var_dump($accessToken->getValue());
          
          // The OAuth 2.0 client handler helps us manage access tokens
          $oAuth2Client = $fb->getOAuth2Client();
          
          // Get the access token metadata from /debug_token
          $tokenMetadata = $oAuth2Client->debugToken($accessToken);
          echo '<h3>Metadata</h3>';
          var_dump($tokenMetadata);
          
          // Validation (these will throw FacebookSDKException's when they fail)
          $tokenMetadata->validateAppId('297674857307164'); // Replace {app-id} with your app id
          // If you know the user ID this access token belongs to, you can validate it here
          //$tokenMetadata->validateUserId('123');
          $tokenMetadata->validateExpiration();
          
          if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
              $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
              echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
              exit;
            }
          
            echo '<h3>Long-lived</h3>';
            var_dump($accessToken->getValue());
          }
          
          $_SESSION['fb_access_token'] = (string) $accessToken;
        }catch(\Exception $ex){
            session()->flash('error', $ex->getMessage());    
            return redirect('/');        
        }
    }
}

class MyLaravelPersistentDataHandler implements \Facebook\PersistentData\PersistentDataInterface
{
  /**
   * @var string Prefix to use for session variables.
   */
  protected $sessionPrefix = 'FBRLH_';

  /**
   * @inheritdoc
   */
  public function get($key)
  {
    return \Session::get($this->sessionPrefix . $key);
  }

  /**
   * @inheritdoc
   */
  public function set($key, $value)
  {
    \Session::put($this->sessionPrefix . $key, $value);
  }
}