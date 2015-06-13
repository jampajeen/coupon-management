<?php

namespace App\Http\Controllers;

use App\utils\datetimeutil;
use DateTime;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use MongoClient;
use MongoId;

class ShopController extends Controller {

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

        return View::make('shop.shop', array('pois' => $pois));
    }

    public function postPreview($id) {
        $data = Input::all();
        $coupon_layouts = array('<img src="/images/coupon_example2.png">');

        if (isset($data['coupon_layouts'])) {
            $coupon_layouts = $data['coupon_layouts'];
        }

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        if ("$id" != "undefined" && isset($id)) {
            $collection = $db->poi;
            $query = array('_id' => new MongoId($id));
            $poi = $collection->findOne($query);
            $layout = array(
                "coupon_layouts" => $coupon_layouts,
                "name" => $poi['name'],
                "addr" => $poi['addr'],
                "zip" => $poi['zip'],
                "phone" => $poi['phone'],
                "href" => $poi['href'],
                "desc" => $poi['desc'],
                "cat" => $poi['cat'],
                "area" => $poi['area'],
                "tzone" => $poi['tzone'],
                "img" => $poi['img'],
                "loc" => $poi['loc']
            );
        } else {

            $name = $data['name'];
            $addr = $data['addr'];
            $zip = $data['zip'];
            $phone = $data['phone'];
            $href = $data['href'];
            $desc = $data['desc'];
            $cat = $data['cat'];
            $area = $data['area'];
            $tzone = $data['tzone'];
            $img = $data['img'];
            $loc = $data['loc'];

            $layout = array(
                "coupon_layouts" => $coupon_layouts,
                "name" => $name,
                "addr" => $addr,
                "zip" => $zip,
                "phone" => $phone,
                "href" => $href,
                "desc" => $desc,
                "cat" => $cat,
                "area" => $area,
                "tzone" => $tzone,
                "img" => $img,
                "loc" => $loc
            );
        }

        return View::make('shop.preview', array('layout' => $layout));
    }

    public function getOverview($id) {

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->poi;
        $query = array('_id' => new MongoId($id));
        $poi = $collection->findOne($query);

        $collection = $db->coupons;
        $query = array('coupon_poi_id' => (string) $poi['_id'], 'status' => 'active');
        $coupons = $collection->find($query);

        return View::make('shop.overview', array('poi' => $poi, 'coupons' => $coupons));
    }

    public function getAddShop() {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->categories;

        $sc = $collection->find();
        $shop_cat = $sc;

        $timezone = datetimeutil::timezone_list();

        $collection = $db->clients;
        $cemail = Session::get("client_email");
        $query = array('client_email' => $cemail);
        $client = $collection->findOne($query);

        $collection = $db->resources;
        $query = array('resource_client_id' => (string) $client['_id'], 'status' => 'active');
        $resources = $collection->find($query)->sort(array('resource_date' => -1));

        $main_url = "http://" . $_SERVER['HTTP_HOST'] . "/";

        return View::make('shop.addshop', array('shop_cat' => $shop_cat, 'client' => $client, 'resources' => $resources, 'main_url' => $main_url, 'timezone' => $timezone));
    }

    public function writeFiles($files, $client_id) {
        $ret = array();
        foreach ($files as $file) {

            $RESOURCES_RELATIVE_PATH = "useruploads";

            $type = "image";

            $path = $file->getRealPath();
            $name = $file->getClientOriginalName();

            $extension = $file->getClientOriginalExtension();

            $size = $file->getSize();

            $mime = $file->getMimeType();

            $destinationPath = public_path() . "/" . $RESOURCES_RELATIVE_PATH . "/" . $type . "/";
            $fileName = uniqid();
            $file->move($destinationPath, $fileName . "." . $extension);

            $main_url = "http://" . $_SERVER['HTTP_HOST'] . "/";
            $pimg = $main_url . $RESOURCES_RELATIVE_PATH . "/" . $type . "/" . $fileName . "." . $extension;

            $ret[] = "$fileName.$extension";

            $dt = new DateTime();
            $now = $dt->format('Y-m-d 00:00:00');

            $server = $_SERVER['HTTP_HOST'];

            $connection = new MongoClient("mongodb://localhost");
            $db = $connection->location;
            $collection = $db->resources;

            $coll_resource = array(
                "resource_client_id" => $client_id,
                "resource_orig_name" => $name,
                "resource_new_name" => $fileName . "." . $extension,
                "resource_path" => $RESOURCES_RELATIVE_PATH . "/" . $type,
                "resource_server" => $server,
                "resource_type" => $type,
                "resource_date" => $now,
                "status" => "active"
            );

            $collection->insert($coll_resource);

            Log::info("File Uploaded");
        }
        return $ret;
    }

    public function postAddShop() {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }
        $cemail = Session::get("client_email");

        $pname = Input::get('name');

        $paddr = Input::get('addr');

        $pzip = Input::get('zip');

        $pphone = Input::get('phone');

        $phref = Input::get('href');

        $pdesc = Input::get('desc');

        $pcat = Input::get('cat');

        $parea = Input::get('area');

        $ptzone = Input::get('tzone');

        $pimg = Input::get('img');

        $plat = Input::get('lat');

        $plon = Input::get('lon');


        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->clients;

        $query = array('client_email' => $cemail);
        $client = $collection->findOne($query);

        $cats = Input::get('cats_max');
        $categories = array();
        for ($i = 0; $i <= $cats; $i++) {
            $main = Input::get("cat_main_$i");
            $sub = Input::get("cat_sub_$i");
            if (isset($main) && isset($sub)) {
                $categories[] = array("main" => $main, "sub" => $sub);
            }
        }

        $files = Input::get('files_max');
        $upload_files = array();
        for ($i = 0; $i <= $files; $i++) {
            if (Input::hasFile("upload_file_$i")) {
                $upload_files[] = Input::file("upload_file_$i");
            }
        }
        $files_arr = self::writeFiles($upload_files, (string) $client['_id']);

        $keyword = Input::get('keyword');
        $keywords = array_map('trim', explode(",", $keyword));
        $keywords = array_filter($keywords);

        $collection = $db->poi;
        $query = array('name' => $pname);
        $poi = $collection->findOne($query);

        /*
         * BEGIN Insert LOGIC
         */
        if (isset($client['client_email']) && isset($poi['name'])) {
            $old_lat = $poi['loc']['lat'];
            $old_lng = $poi['loc']['lon'];

            if ($old_lat == $plat && $old_lng == $plon) {
                /*
                 * this poi is already exist, update $poi_client_id into table only
                 */
                $coll_poi = array(
                    "poi_client_id" => (string) $client['_id'],
                    "img" => $files_arr,
                    "cat" => $categories,
                    "keyword" => $keywords,
                    "status" => "active"
                );

                $newdata = array('$set' => $coll_poi);
                $collection->update(array('_id' => new MongoId($poi['_id'])), $newdata);

                return Redirect::to('shop');
            } else {
                /*
                 * Update both $poi_client_id and $loc
                 */
                $coll_poi = array(
                    "poi_client_id" => (string) $client['_id'],
                    "loc" => array('lon' => floatval($plon), 'lat' => floatval($plat)),
                    "img" => $files_arr,
                    "cat" => $categories,
                    "keyword" => $keywords,
                    "status" => "active"
                );

                $newdata = array('$set' => $coll_poi);
                $collection->update(array('_id' => new MongoId($poi['_id'])), $newdata);

                return Redirect::to('shop');
            }
        } else if (isset($client['client_email'])) {
            /*
             * doesn't exist, insert new data
             */
            $coll_poi = array(
                "poi_client_id" => (string) $client['_id'],
                "name" => $pname,
                "addr" => $paddr,
                "zip" => $pzip,
                "phone" => $pphone,
                "href" => $phref,
                "desc" => $pdesc,
                "area" => $parea,
                "tzone" => $ptzone,
                "img" => $files_arr,
                "cat" => $categories,
                "keyword" => $keywords,
                "loc" => array('lon' => floatval($plon), 'lat' => floatval($plat)),
                "status" => "active"
            );

            $collection->insert($coll_poi);

            return Redirect::to('shop');
        } else {
            Log::info("Can't Insert New POI.");
        }
        /*
         * END Insert LOGIC
         */
    }

    public function getShopItem($id) {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->poi;
        $query = array('_id' => new MongoId($id));
        $poi = $collection->findOne($query);

        return View::make('shop.shopitem', array('poi' => $poi));
    }

    public function getShopItemEdit($id) {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }
        $cemail = Session::get("client_email");
        $updated = Request::query("updated");

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->categories;

        $sc = $collection->find();
        $shop_cat = $sc;

        $timezone = datetimeutil::timezone_list();

        $collection = $db->clients;

        $query = array('client_email' => $cemail);
        $client = $collection->findOne($query);

        $collection = $db->poi;
        $query = array('_id' => new MongoId($id));
        $poi = $collection->findOne($query);

        $collection = $db->resources;
        $query = array('resource_client_id' => (string) $client['_id'], 'status' => 'active');
        $resources = $collection->find($query)->sort(array('resource_date' => -1));

        $main_url = "http://" . $_SERVER['HTTP_HOST'] . "/";

        return View::make('shop.editshop', array('shop_cat' => $shop_cat, 'resources' => $resources, 'main_url' => $main_url, 'client' => $client, 'timezone' => $timezone, 'poi' => $poi, 'updated' => $updated));
    }

    public function writeFiles2($db_files, $files, $files_changed, $client_id) {
        $ret = array();
        $newfile = array();

        foreach ($files as $file) {

            $RESOURCES_RELATIVE_PATH = "useruploads";

            $type = "image";

            $path = $file->getRealPath();
            $name = $file->getClientOriginalName();

            $extension = $file->getClientOriginalExtension();

            $size = $file->getSize();

            $mime = $file->getMimeType();

            $destinationPath = public_path() . "/" . $RESOURCES_RELATIVE_PATH . "/" . $type . "/";
            $fileName = uniqid();
            $file->move($destinationPath, $fileName . "." . $extension);

            $main_url = "http://" . $_SERVER['HTTP_HOST'] . "/";
            $pimg = $main_url . $RESOURCES_RELATIVE_PATH . "/" . $type . "/" . $fileName . "." . $extension;

            // new file name array
            $newfile[] = "$fileName.$extension";

            $dt = new DateTime();
            $now = $dt->format('Y-m-d 00:00:00');

            $server = $_SERVER['HTTP_HOST'];

            $connection = new MongoClient("mongodb://localhost");
            $db = $connection->location;
            $collection = $db->resources;

            $coll_resource = array(
                "resource_client_id" => $client_id,
                "resource_orig_name" => $name,
                "resource_new_name" => $fileName . "." . $extension,
                "resource_path" => $RESOURCES_RELATIVE_PATH . "/" . $type,
                "resource_server" => $server,
                "resource_type" => $type,
                "resource_date" => $now,
                "status" => "active"
            );

            $collection->insert($coll_resource);

            Log::info("File Uploaded");
        }

        $newfile_index = 0;
        foreach ($files_changed as $f) {
            $flag = $f['flag'];
            $value = $f['value'];
            switch ($flag) {
                case "none" : {
                        // do nothing
                    } break;
                case "old" : {
                        $ret[] = $value;
                    } break;
                case "new" : {
                        $ret[] = $newfile[$newfile_index++];
                    } break;
                default: break;
            }
        }

        return $ret;
    }

    public function postShopItemEdit($id) {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }
        $cemail = Session::get("client_email");

        $pname = Input::get('name');

        $paddr = Input::get('addr');

        $pzip = Input::get('zip');

        $pphone = Input::get('phone');

        $phref = Input::get('href');

        $pdesc = Input::get('desc');

        $pcat = Input::get('cat');

        $parea = Input::get('area');

        $ptzone = Input::get('tzone');

        //$pimg = Input::get('img');

        $plat = Input::get('lat');

        $plon = Input::get('lon');


        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->clients;

        $query = array('client_email' => $cemail);
        $client = $collection->findOne($query);


        $collection = $db->poi;
        $query = array('name' => $pname);
        $poi = $collection->findOne($query);

        $cats = Input::get('cats_max');
        $categories = array();
        for ($i = 0; $i <= $cats; $i++) {
            $main = Input::get("cat_main_$i");
            $sub = Input::get("cat_sub_$i");
            if (isset($main) && isset($sub)) {
                $categories[] = array("main" => $main, "sub" => $sub);
            }
        }

        $files_changed = Input::get('files_changed');
        $files_changed = json_decode($files_changed, true);

        $db_files = $poi['img'];

        $files = Input::get('files_max');
        $upload_files = array();
        for ($i = 0; $i <= $files; $i++) {
            if (Input::hasFile("upload_file_$i")) {
                $upload_files[] = Input::file("upload_file_$i");
            }
        }

        $files_arr = self::writeFiles2($db_files, $upload_files, $files_changed, (string) $client['_id']);

        $keyword = Input::get('keyword');
        $keywords = array_map('trim', explode(",", $keyword));
        $keywords = array_filter($keywords);

        $coll_poi = array(
            "name" => $pname,
            "addr" => $paddr,
            "zip" => $pzip,
            "phone" => $pphone,
            "href" => $phref,
            "desc" => $pdesc,
            "area" => $parea,
            "tzone" => $ptzone,
            "img" => $files_arr,
            "cat" => $categories,
            "keyword" => $keywords,
            "loc" => array('lon' => floatval($plon), 'lat' => floatval($plat))
        );

        $newdata = array('$set' => $coll_poi);
        $collection->update(array('_id' => new MongoId($id)), $newdata);

        return Redirect::to('shop/' . $id . '/edit?updated=true');
    }

    public function getDeleteShop($id) {
        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->poi;

        $shop = array(
            "status" => "inactive"
        );

        $newdata = array('$set' => $shop);
        $collection->update(array('_id' => new MongoId($id)), $newdata);

        return Redirect::to('shop');
    }

}
