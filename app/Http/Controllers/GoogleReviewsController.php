<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class GoogleReviewsController extends Controller
{
    protected $cid = "16019254084685826514";
    public function index()
    {
        $scopes = [
            'https://www.googleapis.com/auth/business.manage',
            "https://www.googleapis.com/auth/plus.business.manage"
        ];

        return Socialite::driver('google')->scopes($scopes)->stateless()->redirect();
    }
    public function callBackGoogle()
    {
       // $user =  Socialite::with('google')->stateless()->user();

        $credentials = storage_path('client_secret.json');
        $client = new \Google\Client();
        $client->setAuthConfig($credentials);
        $client->addScope("https://www.googleapis.com/auth/business.manage");
        $client->addScope("https://www.googleapis.com/auth/plus.business.manage");
        $redirect_uri = env('GOOGLE_REDIRECT_URI');

        $client->setRedirectUri($redirect_uri);
        $my_business_account = new \Google_Service_MyBusiness($client);

        if (isset($_GET['logout'])) { // logout: destroy token
            unset($_SESSION['token']);
            die('Logged out.');
        }

        if (isset($_GET['code'])) { // get auth code, get the token and store it in session
            $client->authenticate($_GET['code']);
            $_SESSION['token'] = $client->getAccessToken();
        }


        if (isset($_SESSION['token'])) { // get token and configure client
            $token = $_SESSION['token'];
            $client->setAccessToken($token);
        }

        if (!$client->getAccessToken()) { // auth call
            $authUrl = $client->createAuthUrl();
            header("Location: " . $authUrl);
            die;
        }

        dd($my_business_account);
        dd(($my_business_account->accounts->listAccounts()));

        // ┌─────────────────────────────────────────────────────────────────────────┐
        // │                    GMB API Calls - Get The Account                      │
        // └─────────────────────────────────────────────────────────────────────────┘

        $list_accounts_response = $my_business_account->accounts->listAccounts();


        // ┌─────────────────────────────────────────────────────────────────────────┐
        // │                            OUTPUT RESULT                                │
        // └─────────────────────────────────────────────────────────────────────────┘

        echo '<pre>';
        echo print_r($list_accounts_response, true);
        echo '</pre>';
        dd($my_business_account->accounts->listAccounts());
    }
}
