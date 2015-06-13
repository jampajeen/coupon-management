<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use DateTime;
use DOMDocument;
use MongoClient;
use MongoCollection;
use MongoId;
use App\utils\tmhOAuth;
use App\utils\Instagram;


class SocialController extends Controller {
    
    /*
     * allow cross-domain request
     */
    public function __construct() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
    }
    
    public function APIgetInstagramCallback() {

        $code = Request::query('code');
        if (isset($code)) {
            return "$code";
        } else {
            return "";
        }
    }
 
    public function APIgetInstagramContentByHashTag($hashtag) {
        
        $apiKey = Config::get('socialnetwork.instagram.apiKey');
        $apiSecret = Config::get('socialnetwork.instagram.apiSecret');
        $apiCallback = Config::get('socialnetwork.instagram.apiCallback');
        $client_id = Config::get('socialnetwork.instagram.client_id');
        $username = Config::get('socialnetwork.instagram.username');
        $password = Config::get('socialnetwork.instagram.password');
        $useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.104 Safari/537.36';
        
        $ckfile = storage_path()."/sessions/CURLCOOKIE";//tempnam (storage_path()."/sessions", "CURLCOOKIE");
        if(!file_exists($password)) {
            touch($ckfile);
        }
        
        /*
         * First oauth authentication URL
         */
        $url = 'https://instagram.com/oauth/authorize?client_id='.$client_id.'&redirect_uri='.$apiCallback.'&scope=basic&response_type=code';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
        curl_setopt ($ch, CURLOPT_COOKIEFILE, $ckfile);
        curl_setopt( $ch, CURLOPT_USERAGENT, $useragent );

        curl_setopt($ch, CURLOPT_URL, $url);
        $out = curl_exec($ch);
        $html_authorize = $out;
        $out = str_replace("\r", "", $out);

        $headers_end = strpos($out, "\n\n");
        if ($headers_end !== false) {
            $out = substr($out, 0, $headers_end);
        }
        $headers = explode("\n", $out);

        foreach ($headers as $header) {
            if (substr($header, 0, 10) == "Location: ") {
                $location = substr($header, 10);
                break;
            }
        }
        
        $after = substr( $location, 0, 36);
        
        /*
         * If Redirect to Login page
         */
        if(strcmp($after, "https://instagram.com/accounts/login") == 0) {
            
            $url = $location;
            /*
             * Request login page
             */
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
            curl_setopt ($ch, CURLOPT_COOKIEFILE, $ckfile);
            curl_setopt( $ch, CURLOPT_USERAGENT, $useragent );
            $html = curl_exec($ch);
            
            $out = $html;
            $out = str_replace("\r", "", $out);

            $headers_end = strpos($out, "\n\n");
            if ($headers_end !== false) {
                $out = substr($out, 0, $headers_end);
            }
            $headers = explode("\n", $out);

            foreach ($headers as $header) {
                if (substr($header, 0, 10) == "Location: ") {
                    $location = substr($header, 10);
                    break;
                }
            }
            
            $dom = new DOMDocument;
            libxml_use_internal_errors(true);
            $dom->loadHTML($html); 
            $dom->preserveWhiteSpace = false;
            
            $inputs = $dom->getElementsByTagName('input');
            foreach ($inputs as $input) {
                if(strcmp("csrfmiddlewaretoken",$input->getAttribute('name')."") == 0) {
                    $csrfmiddlewaretoken = $input->getAttribute('value')."";
                    break;
                }
            }
            
            /*
             * Login & Get Access token from callback url
             */
            $fields = array(
                'csrfmiddlewaretoken' => ($csrfmiddlewaretoken),
                'username' => ($username),
                'password' => ($password)
            );
            $fields_string = "";
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            $fields_string = substr($fields_string, 0, strlen($fields_string) - 1);
            
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'Accept-Language: th,en-US;q=0.8,en;q=0.6'));
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch,CURLOPT_POST, true);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt( $ch, CURLOPT_REFERER, $url );
            curl_setopt( $ch, CURLOPT_USERAGENT, $useragent );
            curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
            curl_setopt ($ch, CURLOPT_COOKIEFILE, $ckfile);

            $code = curl_exec( $ch );
            
            curl_close( $ch );
            
        } else { /* If already has authorize, get token  */
            
            $out = str_replace("\r", "", $html_authorize);
            $ar = explode("\n", $out);
            
            if(isset($ar) && !empty($ar[count($ar) - 1 ])) {
                $code =  "".$ar[count($ar) - 1 ];
            }
        }
        
        if (!empty($code)) {
            
            $instagram = new Instagram(array(
                'apiKey' => $apiKey,
                'apiSecret' => $apiSecret,
                'apiCallback' => $apiCallback
            ));
            
            $data = $instagram->getOAuthToken($code);
            $instagram->setAccessToken(json_decode($data)->access_token);
            $result = ($instagram->getPublicMedia($hashtag));
            
            return Response::json(['status' => 200, 'success' => true, 'text' => "Request Instagram API successfully", 'data' => $result])->setCallback(Input::get('jsoncallback'));
        } else {
            return Response::json(['status' => 200, 'success' => false, 'text' => "Error Request Instagram API", 'data' => "[]"])->setCallback(Input::get('jsoncallback'));
        }
        
    }
    
    public function APIgetTwitterContentByHashTag($hashtag) {
        
        $consumer_key = Config::get('socialnetwork.twitter.consumer_key');
        $consumer_secret = Config::get('socialnetwork.twitter.consumer_secret');
        $user_token = Config::get('socialnetwork.twitter.user_token');
        $user_secret = Config::get('socialnetwork.twitter.user_secret');
        
        $connection = new tmhOAuth(array(
        'consumer_key' => $consumer_key,
        'consumer_secret' => $consumer_secret,
        'user_token' => $user_token,
        'user_secret' => $user_secret
            ));
        
        
        $http_code = $connection->request('GET', $connection->url('1.1/search/tweets'), array('q' => $hashtag, 'count' => 10, 'lang' => 'en'));

        if ($http_code == 200) {

            $response = json_decode($connection->response['response'], true);
            $tweet_data = $response['statuses'];

            $hasTweet = false;
            $tweet_stream = '[';
            foreach ($tweet_data as $tweet) {
                $tweet_stream .= ' { "tweet": ' . json_encode($tweet['text']) . ' },';
                $hasTweet = true;
            }
            if ($hasTweet) {
                $tweet_stream = substr($tweet_stream, 0, -1);
            }
            $tweet_stream .= ']';

        } else {
            if ($http_code == 429) {
                return Response::json(['status' => 200, 'success' => false, 'text' => "Error: Twitter API rate limit reached"])->setCallback(Input::get('jsoncallback'));
            } else {
                return Response::json(['status' => 200, 'success' => false, 'text' => "Error: Twitter was not able to process that request"])->setCallback(Input::get('jsoncallback'));
            }
        }
        return Response::json(['status' => 200, 'success' => true, 'text' => "Request Twitter API successfully", 'data' => json_decode($tweet_stream)])->setCallback(Input::get('jsoncallback'));
    }
    
}
