<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Oseintow\Bigcommerce\Bigcommerce;
use App\Models\Shop;
use Bigcommerce\Api\Client as Bigcommerce_api;
use Session;
class MainController extends BaseController

{
    protected $baseURL;

    public function __construct()
    {
        $this->baseURL = env('APP_URL');
    }

    public function getAppClientId() {
        if (env('APP_ENV') === 'local') {
            return env('BC_LOCAL_CLIENT_ID');
        } else {
            return env('BC_APP_CLIENT_ID');
        }
    }

    public function getAppSecret(Request $request) {
        if (env('APP_ENV') === 'local') {
            return env('BC_LOCAL_SECRET');
        } else {
            return env('BC_APP_SECRET');
        }
    }

    public function getAccessToken(Request $request) {
        if (env('APP_ENV') === 'local') {
            return env('BC_LOCAL_ACCESS_TOKEN');
        } else {
            return $request->session()->get('access_token');
        }
    }

    public function getStoreHash(Request $request) {
        if (env('APP_ENV') === 'local') {
            return env('BC_LOCAL_STORE_HASH');
        } else {
            return $request->session()->get('store_hash');
        }
    }

    public function install(Request $request)
    {
        // Make sure all required query params have been passed
        if (!$request->has('code') || !$request->has('scope') || !$request->has('context')) {
            return redirect()->action('MainController@error')->with('error_message', 'Not enough information was passed to install this app.');
        }

        try {

            // dd([
            //     'json' => [
            //         'client_id' => $this->getAppClientId(),
            //         'client_secret' => $this->getAppSecret($request),
            //         'redirect_uri' => url('/auth/install'),
            //         'grant_type' => 'authorization_code',
            //         'code' => $request->input('code'),
            //         'scope' => $request->input('scope'),
            //         'context' => $request->input('context'),
            //     ]
            // ]);

            $client = new Client();
            $result = $client->request('POST', 'https://login.bigcommerce.com/oauth2/token', [
                'json' => [
                    'client_id' => $this->getAppClientId(),
                    'client_secret' => $this->getAppSecret($request),
                    'redirect_uri' => url('/auth/install'),
                    'grant_type' => 'authorization_code',
                    'code' => $request->input('code'),
                    'scope' => $request->input('scope'),
                    'context' => $request->input('context'),
                ]
            ]);

            $statusCode = $result->getStatusCode();
            $data = json_decode($result->getBody(), true);

            if ($statusCode == 200) {
                //127 net work để lấy auth token...
                // dd($data['access_token']);
                $request->session()->put('store_hash', $data['context']);
                $request->session()->put('access_token', $data['access_token']);
                $request->session()->put('user_id', $data['user']['id']);
                $request->session()->put('user_email', $data['user']['email']);

                
                // luưu thông tin của auth token vào db ...
                Shop::create([
                    'access_token' => $data['access_token'],
                    'App_name' => $data['context'],
                ]);





                // If the merchant installed the app via an external link, redirect back to the 
                // BC installation success page for this app
                if ($request->has('external_install')) {
                    return redirect('https://login.bigcommerce.com/app/' . $this->getAppClientId() . '/auth/test');
                }
            }

            return redirect('/');
        } catch (RequestException $e) {

            dd($e->getMessage());

            $statusCode = $e->getResponse()->getStatusCode();
            $errorMessage = "An error occurred.";

            if ($e->hasResponse()) {
                if ($statusCode != 500) {
                    $errorMessage = Psr7\str($e->getResponse());
                }
            }

            // If the merchant installed the app via an external link, redirect back to the 
            // BC installation failure page for this app
            if ($request->has('external_install')) {
                return redirect('https://login.bigcommerce.com/app/' . $this->getAppClientId() . '/install/failed');
            } else {
                return redirect()->action('MainController@error')->with('error_message', $errorMessage);
            }
        }
    }

    public function load(Request $request)
    {
        $signedPayload = $request->input('signed_payload');
        if (!empty($signedPayload)) {
            $verifiedSignedRequestData = $this->verifySignedRequest($signedPayload, $request);
            if ($verifiedSignedRequestData !== null) {
                $request->session()->put('user_id', $verifiedSignedRequestData['user']['id']);
                $request->session()->put('user_email', $verifiedSignedRequestData['user']['email']);
                $request->session()->put('owner_id', $verifiedSignedRequestData['owner']['id']);
                $request->session()->put('owner_email', $verifiedSignedRequestData['owner']['email']);
                $request->session()->put('store_hash', $verifiedSignedRequestData['context']);
            } else {
                return redirect()->action('MainController@error')->with('error_message', 'The signed request from BigCommerce could not be validated.');
            }
        } else {
            return redirect()->action('MainController@error')->with('error_message', 'The signed request from BigCommerce was empty.');
        }

        return redirect('/');
    }

    public function error(Request $request)
    {
        $errorMessage = "Internal Application Error";

        if ($request->session()->has('error_message')) {
            $errorMessage = $request->session()->get('error_message');
        }

        echo '<h4>An issue has occurred:</h4> <p>' . $errorMessage . '</p> <a href="'.$this->baseURL.'">Go back to home</a>';
    }

    private function verifySignedRequest($signedRequest, $appRequest)
    {
        list($encodedData, $encodedSignature) = explode('.', $signedRequest, 2);

        // decode the data
        $signature = base64_decode($encodedSignature);
            $jsonStr = base64_decode($encodedData);
        $data = json_decode($jsonStr, true);

        // confirm the signature
        $expectedSignature = hash_hmac('sha256', $jsonStr, $this->getAppSecret($appRequest), $raw = false);
        if (!hash_equals($expectedSignature, $signature)) {
            error_log('Bad signed request from BigCommerce!');
            return null;
        }
        return $data;
    }

    public function test(Request $request){
    
        // dd(Session::get('access_token') );
        Bigcommerce_api::configure(array(
            'client_id' => 'cpbbu22ejcr0svkcvzts5bvah8zg79y',
            'auth_token' => 'musuxyixe9168ud5dlc2idqvrdptyyv',
            'store_hash' => 'wuvtftew7m'
        ));





        // Bigcommerce_api::configure(array(
        //     'store_url' => 'https://hnacouq.mybigcommerce.com',
        //     'username'	=> 'hnacouqpls',
        //     'api_key'	=> 'aa90pfvbskgjveq9hz5eenyucjcgltf'
        // ));

        // $filter = array("page" => 1, "limit" => 5);


        $products = Bigcommerce_api::getProducts();
        
        return view('welcome');

        

        
    }

    public function test2(){
        Bigcommerce_api::configure(array(
            'client_id' => 'cpbbu22ejcr0svkcvzts5bvah8zg79y',
            'auth_token' => 'musuxyixe9168ud5dlc2idqvrdptyyv',
            'store_hash' => 'wuvtftew7m'
        ));
        $products = Bigcommerce_api::getProducts();
        // $product->name = "MacBook Air";
        // $product->price = 99.95;
        // $product->update();
        
        dd($products);
        $client = new Client();
        $res = $client->request('get', 'https://api.bigcommerce.com/stores/wuvtftew7m/v3/catalog/products',[
            'headers'        => ['Accept' => 'application/json','X-Auth-Token' => 'musuxyixe9168ud5dlc2idqvrdptyyv'
        ],
        ]);
        $data = json_decode($res->getBody());
        dd($data);
        return response()->json($data);
    }

    public function makeBigCommerceAPIRequest(Request $request, $endpoint)
    {
        $requestConfig = [
            'headers' => [
                'X-Auth-Client' => $this->getAppClientId(),
                'X-Auth-Token'  => $this->getAccessToken($request),
                'Content-Type'  => 'application/json',
            ]
        ];

        if ($request->method() === 'PUT') {
            $requestConfig['body'] = $request->getContent();
        }

        $client = new Client();
        $result = $client->request($request->method(), 'https://api.bigcommerce.com/' . $this->getStoreHash($request) . '/' . $endpoint, $requestConfig);
        return $result;
    }

    public function proxyBigCommerceAPIRequest(Request $request, $endpoint)
    {
        if (strrpos($endpoint, 'v2') !== false) {
            // For v2 endpoints, add a .json to the end of each endpoint, to normalize against the v3 API standards
            $endpoint .= '.json';
        }

        $result = $this->makeBigCommerceAPIRequest($request, $endpoint);

        return response($result->getBody(), $result->getStatusCode())->header('Content-Type', 'application/json');
    }
}
