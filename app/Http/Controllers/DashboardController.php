<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use MongoClient;

class DashboardController extends Controller {

    public function getIndex() {

        if (!Session::has('client_email')) {
            return Redirect::to('clients/login');
        }
        $active_route = "/dashboard";

        $cemail = Session::get("client_email");

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;


        $collection = $db->clients;
        $query = array('client_email' => $cemail);
        $client = $collection->findOne($query);

        $collection = $db->poi;
        $poi_count = $collection->count(array('poi_client_id' => (string) $client['_id'], 'status' => 'active'));

        $query = array('poi_client_id' => (string) $client['_id'], 'status' => 'active');
        $pois = $collection->find($query);

        $pid = Array();
        foreach ($pois as $poi) {
            $pid[] = (string) $poi['_id'];
        }

        $collection = $db->coupons;
        $query = array('coupon_poi_id' => array('$in' => $pid), 'status' => 'active');
        $coupon_count = $collection->count($query);

        $coupons = $collection->find($query);

        $additional = array();
        $additional["coupon_count"] = $coupon_count;
        $additional["poi_count"] = $poi_count;

        return View::make('dashboard.dashboard', array('client' => $client, 'pois' => $pois, 'coupons' => $coupons, 'additional' => $additional));
    }

}
