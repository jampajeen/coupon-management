<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use MongoClient;

class UserController extends Controller {
    
    /*
     * allow cross-domain request
     */
    public function __construct() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
    }
    
    public function APIpostLogin() {

        $email = Input::get('email');
        $password = Input::get('password');
        Log::info($email);
        Log::info($password);
        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->users;
        $query = array('email' => $email, 'password' => md5($password));

        $item = $collection->findOne($query);

        if (isset($item['email'])) {
            return Response::json(['status' => 200, 'success' => true , 'text' => 'login successfully'])->setCallback(Input::get('jsoncallback'));
        } else {
            return Response::json(['status' => 200, 'success' => false , 'text' => 'email or password mismatch'])->setCallback(Input::get('jsoncallback'));
        }
        
    }
    
    
    public function APIpostRegister() {

        $email = Input::get('email');
        $password = md5(Input::get('password'));
        $dt = new DateTime();
        $join_date = $dt->format('Y-m-d H:i:s'); //"2013-11-03T12:44:31+0000"
        
        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->users;
        $query = array('email' => $email);

        $item = $collection->findOne($query);


        if (!isset($item['email'])) {
            $coll = array(
                "id" => uniqid(),
                "email" => $email,
                "password" => $password,
                "first_name" => null,
                "gender" => null,
                "last_name" => null,
                "link" => null,
                "locale" => "th_TH",
                "name" => null,
                "timezone" => "7",
                "updated_time" => $join_date,
                "verified" => false,
                "source" => "email"
            );

            $collection->insert($coll);
            
            return Response::json(['status' => 200, 'success' => true , 'text' => 'Register successfully'])->setCallback(Input::get('jsoncallback'));
        } else {
            return Response::json(['status' => 200, 'success' => false , 'text' => 'Your email is already exist'])->setCallback(Input::get('jsoncallback'));
        }

    }

}
