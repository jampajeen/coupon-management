<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use MongoClient;
use MongoId;

class CouponController extends Controller {

    public function getIndex() {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }
        $cemail = Session::get("client_email");

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->clients;
        $query = array('client_email' => $cemail);
        $client = $collection->findOne($query);

        $collection = $db->poi;
        $query = array('poi_client_id' => (string) $client['_id'], 'status' => 'active');
        $pois = $collection->find($query);

        $collection = $db->coupons;

        $poi_id = Array();
        foreach ($pois as $poi) {
            $poi_id[] = (string) $poi['_id'];
        }

        $query = array(
            '$and' => array(
                array(
                    'status' => 'active'
                ),
                array(
                    'coupon_poi_id' => array('$in' => $poi_id)
                )
            )
        );

        $coupons = $collection->find($query);

        return View::make('coupon.coupon', array('pois' => $pois, 'coupons' => $coupons));
    }

    public function getDeleteCoupon($id) {
        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->coupons;

        $coupon = array(
            "status" => "inactive"
        );

        $newdata = array('$set' => $coupon);
        $collection->update(array('_id' => new MongoId($id)), $newdata);

        return Redirect::to('coupon');
    }

    public function getCouponItemEdit($id) {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }
        $cemail = Session::get("client_email");
        $updated = Request::query('updated');

        $dt = new DateTime();
        $now = $dt->format('Y-m-d 00:00');


        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->clients;
        $query = array('client_email' => $cemail);
        $client = $collection->findOne($query);

        $collection = $db->poi;
        $query = array('poi_client_id' => (string) $client['_id'], 'status' => 'active');
        $pois = $collection->find($query);

        $collection = $db->coupons;

        foreach ($pois as $poi) {
            $poi_id[] = (string) $poi['_id'];
        }

        $query = array('_id' => new MongoId($id));
        $coupon = $collection->findOne($query);

        $file = public_path() . "/coupontemplate/" . $coupon['_id'] . ".html";
        $current_template_content = file_get_contents($file);


        /*
         * ======== This is Important ======== 
         */
        $current_template_content = substr($current_template_content, strpos($current_template_content, "\n") + 1);

        $collection = $db->resources;
        $query = array('resource_client_id' => (string) $client['_id'], 'status' => 'active');
        $resources = $collection->find($query)->sort(array('resource_date' => -1));

        $main_url = "http://" . $_SERVER['HTTP_HOST'] . "/";

        return View::make('coupon.editcoupon', array('pois' => $pois, 'coupon' => $coupon, 'resources' => $resources, 'main_url' => $main_url, 'current_template_content' => $current_template_content, 'updated' => $updated));
    }

    public function getAddCoupon() {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }
        $cemail = Session::get("client_email");

        $shop_cat = array("restaurant", "hospital", "beauty & salon", "fashion", "gas", "cafe");

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->clients;
        $query = array('client_email' => $cemail);
        $client = $collection->findOne($query);

        $collection = $db->poi;
        $query = array('poi_client_id' => (string) $client['_id'], 'status' => 'active');
        $pois = $collection->find($query);

        $dt = new DateTime();
        $now = $dt->format('Y-m-d 00:00');

        $collection = $db->resources;
        $query = array('resource_client_id' => (string) $client['_id'], 'status' => 'active');
        $resources = $collection->find($query)->sort(array('resource_date' => -1));

        $main_url = "http://" . $_SERVER['HTTP_HOST'] . "/";

        return View::make('coupon.addcoupon', array('resources' => $resources, 'main_url' => $main_url, 'now' => $now, 'pois' => $pois, 'shop_cat' => $shop_cat));
    }

    public function getCouponItem($id) {
        return View::make('coupon.couponitem', array('id' => $id));
    }

    public function postAddCoupon() {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }
        $cemail = Session::get("client_email");

        $check_days = (array) Input::get('check_days');

        $times = Input::get('times_max');
        $coupon_target_times = array();
        for ($i = 0; $i < $times; $i++) {
            $begin = Input::get("coupon_begin_time_$i");
            $finish = Input::get("coupon_finish_time_$i");
            if (isset($begin) && isset($finish)) {
                $coupon_target_times[] = array("begin" => $begin, "finish" => $finish);
            }
        }

        $area = Input::get('area');
        $areas = array_map('trim', explode(",", $area));

        $coupon_begin_date = Input::get('coupon_begin_date');
        $coupon_finish_date = Input::get('coupon_finish_date');
        $coupon_startdate = $coupon_begin_date . " 00:00:00";
        $coupon_enddate = $coupon_finish_date . " 00:00:00";

        $coupon_layout = Input::get('coupon_layout');
        $coupon_name = Input::get('coupon_name');
        $coupon_desc = Input::get('coupon_desc');

        $coupon_price = Input::get('coupon_price');
        $coupon_amt = Input::get('coupon_amt');
        $poi_id = Input::get('poi_id');

        $coupon_href = Input::get('$coupon_href');

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->coupons;

        $coll_coupon = array(
            "coupon_poi_id" => $poi_id,
            "coupon_name" => $coupon_name,
            "coupon_startdate" => $coupon_startdate,
            "coupon_enddate" => $coupon_enddate,
            "coupon_target_days" => $check_days,
            "coupon_target_times" => $coupon_target_times,
            "coupon_areas" => $areas,
            "coupon_href" => $coupon_href,
            "coupon_price" => $coupon_price,
            "coupon_amt" => $coupon_amt,
            "coupon_desc" => $coupon_desc,
            "status" => "active"
        );

        $coll_coupon['_id'] = new MongoId();
        $collection->insert($coll_coupon);

        $file = public_path() . "/coupontemplate/" . $coll_coupon['_id'] . ".html";

        $css = "<link rel=\"stylesheet\" href=\"vendor/uikit/css/uikit.gradient.min.css\">";
        $css = $css . "<link rel=\"stylesheet\" href=\"css/style.css\">";
        $css = $css . "<link rel=\"stylesheet\" href=\"css/coupon.css\">";
        $coupon_layout = $css . "\n" . $coupon_layout;
        file_put_contents($file, $coupon_layout);

        if (Input::hasFile('file')) {

            //Input::file('upload_image')->move($destinationPath);

            $path = Input::file('file')->getRealPath();
            $name = Input::file('file')->getClientOriginalName();

            $extension = Input::file('file')->getClientOriginalExtension();

            $size = Input::file('file')->getSize();

            $mime = Input::file('file')->getMimeType();

            $destinationPath = public_path() . "/useruploads/image/";
            $fileName = uniqid();
            Input::file('file')->move($destinationPath, $fileName . "." . $extension);
        }

        return Redirect::to('/coupon');
    }

    public function postCouponItemEdit($id) {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }
        $cemail = Session::get("client_email");

        $check_days = (array) Input::get('check_days');

        $times = Input::get('times_max');
        $coupon_target_times = array();
        for ($i = 0; $i < $times; $i++) {
            $begin = Input::get("coupon_begin_time_$i");
            $finish = Input::get("coupon_finish_time_$i");
            if (isset($begin) && isset($finish)) {
                $coupon_target_times[] = array("begin" => $begin, "finish" => $finish);
            }
        }

        $area = Input::get('area');
        $areas = array_map('trim', explode(",", $area));

        $coupon_begin_date = Input::get('coupon_begin_date');
        $coupon_finish_date = Input::get('coupon_finish_date');
        $coupon_startdate = $coupon_begin_date . " 00:00:00";
        $coupon_enddate = $coupon_finish_date . " 00:00:00";

        $coupon_layout = Input::get('coupon_layout');
        $html_editor = Input::get('htmleditor_content');
        $coupon_name = Input::get('coupon_name');
        $coupon_desc = Input::get('coupon_desc');
        $coupon_price = Input::get('coupon_price');
        $coupon_amt = Input::get('coupon_amt');
        $poi_id = Input::get('poi_id');

        $coupon_href = Input::get('$coupon_href');

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->coupons;

        $coll_coupon = array(
            "coupon_poi_id" => $poi_id,
            "coupon_name" => $coupon_name,
            "coupon_startdate" => $coupon_startdate,
            "coupon_enddate" => $coupon_enddate,
            "coupon_target_days" => $check_days,
            "coupon_target_times" => $coupon_target_times,
            "coupon_areas" => $areas,
            "coupon_href" => $coupon_href,
            "coupon_price" => $coupon_price,
            "coupon_amt" => $coupon_amt,
            "coupon_desc" => $coupon_desc,
            "status" => "active"
        );


        $newdata = array('$set' => $coll_coupon);
        $collection->update(array('_id' => new MongoId($id)), $newdata);


        $file = public_path() . "/coupontemplate/" . $id . ".html";
        $css = "<link rel=\"stylesheet\" href=\"vendor/uikit/css/uikit.gradient.min.css\">";
        $css = $css . "<link rel=\"stylesheet\" href=\"css/style.css\">";
        $css = $css . "<link rel=\"stylesheet\" href=\"css/coupon.css\">";
        $coupon_layout = $css . "\n" . $coupon_layout;
        file_put_contents($file, $coupon_layout);

        if (Input::hasFile('file')) {

            $path = Input::file('file')->getRealPath();
            $name = Input::file('file')->getClientOriginalName();

            $extension = Input::file('file')->getClientOriginalExtension();

            $size = Input::file('file')->getSize();

            $mime = Input::file('file')->getMimeType();

            $destinationPath = public_path() . "/useruploads/image/";
            $fileName = uniqid();
            Input::file('file')->move($destinationPath, $fileName . "." . $extension);
        }
        return Redirect::to('/coupon/' . $id . '/edit?updated=true');
    }

}
