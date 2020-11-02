<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Bigcommerce\Api\Client as Bigcommerce_api;
use App\Models\Shop;
use GuzzleHttp\Psr7\Request as Req;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    //
    public function index(){
    //     Bigcommerce_api::configure(array(
    //         'client_id' => 'cpbbu22ejcr0svkcvzts5bvah8zg79y',
    //         'auth_token' => 'musuxyixe9168ud5dlc2idqvrdptyyv',
    //         'store_hash' => 'wuvtftew7m'
    //     ));

    //     $newPr = [];
    //     $products = Bigcommerce_api::getProducts();
    //     // foreach($products as $product)
        

    //     // dd($products);
    //     foreach($products as $val){
    //        $newPr[] = [
    //            'id'=> $val->id,
    //            'name' => $val->name,
    //            'image' => $val->primary_image->standard_url,
    //            'price' => $val->price,
    //        ];
    //     }
     //lấy token từ database   
    $data = Shop::orderBy('id','DESC')->first();
    $token = $data->access_token;   

    // // $shop =  Shop::all();
    // // dd($shop);
    // return response()->json(array_values($newPr));
    $client = new Client();
        $res = $client->request('get', 'https://api.bigcommerce.com/stores/wuvtftew7m/v3/catalog/products',
        [
            'headers' => [
                            'Accept' => 'application/json',
                            'X-Auth-Token' => $token,
                        ],
        ]);
        $data = json_decode($res->getBody());
        return response()->json($data);

      

    
        
        

        
    
        
    }

    public function show($id){
        // Bigcommerce_api::configure(array(
        //     'client_id' => 'cpbbu22ejcr0svkcvzts5bvah8zg79y',
        //     'auth_token' => 'musuxyixe9168ud5dlc2idqvrdptyyv',
        //     'store_hash' => 'wuvtftew7m'
        // ));
        // $data = [];
        // $product = Bigcommerce_api::getProduct($id);

        // $data = [
        //     'id' => $product->id,
        //     'name' => $product->name,
        //     'image' => $product->primary_image->standard_url,
        //     'price' => $product->price,
        // ];

        // return response()->json($data);
        $client = new Client();
         //lấy token từ database
        $data = Shop::orderBy('id','DESC')->first();
        $token = $data->access_token;
        $url = 'https://api.bigcommerce.com/stores/wuvtftew7m/v3/catalog/products/'.$id;  
        
        $res = $client->request('GET',$url,[
            'headers' => [
                'Accept' => 'application/json',
                'X-Auth-Token' => $token,
            ]
        ]);

        $data = json_decode($res->getBody());
        return response()->json($data);




    }

    public function destroy($id){
        $client = new Client();
        // lấy token từ database
        $data = Shop::orderBy('id','DESC')->first();
        $token = $data->access_token;
        $url = 'https://api.bigcommerce.com/stores/wuvtftew7m/v3/catalog/products/'.$id;  
        
        $res = $client->request('delete',$url,[
            'headers' => [
                'Accept' => 'application/json',
                'X-Auth-Token' => $token,
            ]
        ]);

        return response()->json([
            'message' => 'Delete Successfully...'
        ]);
    }

    public function pro(Request $request){

        
    //     $client = new Client();
    //     $response = $client->request('GET', 'http://localhost:2298/products');


    //    dd($response);
    }

    public function update($id,Request $request){
        // dd($request->all());
        $client = new Client();
        //lấy token từ database
        $data = Shop::orderBy('id','DESC')->first();
        $token = $data->access_token;
        $url = 'https://api.bigcommerce.com/stores/wuvtftew7m/v3/catalog/products/'.$id;  
        
        $res = $client->request('put',$url,[
            'headers' => [
                'Accept' => 'application/json',
                'X-Auth-Token' => $token,
            ],
            'json' =>  $request->all(),
            
        ]);

        return response()->json([
            'message' => 'Update Product Successfully...',
        ]);

    }
}
