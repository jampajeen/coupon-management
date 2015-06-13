<?php

namespace App\Http\Controllers;

use App\utils\datautil;
use App\utils\datetimeutil;
use DateTime;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use MongoClient;
use MongoId;

class AdminController extends Controller {

    public function getAdminLogin() {

        $err = Request::query('err');
        $error = isset($err) ? Lang::get("message.$err") : "";

        return View::make('admin.admin', array('error' => $error)); //->with('error', $error);
    }

    public function postAdminLogin() {

        $username = Input::get('username');
        $password = Input::get('password');

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->admin;
        $query = array('username' => $username, 'password' => ($password));

        $item = $collection->findOne($query);

        if (isset($item['username'])) {

            Session::put('admin', $username);
            return Redirect::to('admin/dashboard');
        } else {

            $err = "login_error_user_passwd";
            return Redirect::to('admin?err=' . $err);
        }
    }

    public function getAdminLogout() {

        if (Session::has("admin")) {
            Session::forget("admin");
        }

        return Redirect::to('admin');
    }

    public function getAdminDashboard() {
        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }
        $active_route = "/admin/dashboard";
        return View::make('admin.dashboard', array('active_route' => $active_route));
    }

    public function getAdminClient() {

        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }
        $active_route = "/admin/clients";

        $search_text = Request::query('search_text');

        $docs_per_page = 2;
        $page = Request::query('page');

        if (!isset($page)) {
            $page = 1;
        }

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->clients;

        $skip = (int) ($docs_per_page * ($page - 1));
        $limit = $docs_per_page;

        if (isset($search_text)) {
            $query = array(
                '$or' => array(
                    array(
                        'client_name' => array('$regex' => "$search_text")
                    ),
                    array(
                        'client_email' => array('$regex' => "$search_text")
                    )
                )
            );
            $cursor = $collection->find($query)->limit($limit)->skip($skip);
        } else {
            $cursor = $collection->find()->limit($limit)->skip($skip);
        }

        $total_documents = $cursor->count();
        $clients = $cursor;

        $client_additional = array();

        foreach ($clients as $client) {
            $collection = $db->poi;
            $poi_count = $collection->count(array('poi_client_id' => (string) $client['_id']));

            $pois = $collection->find(array('poi_client_id' => (string) $client['_id']));

            $pid = Array();
            foreach ($pois as $poi) {
                $pid[] = (string) $poi['_id'];
            }

            $collection = $db->coupons;
            $coupon_count = $collection->count(array('coupon_poi_id' => array('$in' => $pid)));

            $client_additional[(string) $client['_id']] = array("poi_count" => $poi_count, "coupon_count" => $coupon_count);
        }

        $table_additional = datautil::makePagination($total_documents, $docs_per_page, $page);

        return View::make('admin.clients', array('active_route' => $active_route, 'clients' => $clients,
                    'total_documents' => $total_documents, 'search_text' => $search_text, 'client_additional' => $client_additional,
                    'table_additional' => $table_additional));
    }

    public function getAdminClientAccountDelete($id) {
        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }
        $search_text = Request::query('search_text');
        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->clients;
        $client = $collection->findOne(array('_id' => new MongoId($id)));
        $status = ($client['status'] == "active" ? "inactive" : "active");

        $query = array(
            "status" => $status
        );

        $newdata = array('$set' => $query);
        $collection->update(array('_id' => new MongoId($id)), $newdata);

        return Redirect::to('admin/clients?search_text=' . $search_text);
    }

    public function getAdminPoi() {
        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }
        $active_route = "/admin/poi";

        $search_text = Request::query('search_text');

        $docs_per_page = 20;
        $page = Request::query('page');

        if (!isset($page)) {
            $page = 1;
        }

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->poi;

        $skip = (int) ($docs_per_page * ($page - 1));
        $limit = $docs_per_page;

        if (isset($search_text) && !empty($search_text)) {
            $query = array('name' => array('$regex' => "$search_text"));
            $cursor = $collection->find($query)->limit($limit)->skip($skip);
        } else {
            $cursor = $collection->find()->limit($limit)->skip($skip);
        }

        $total_documents = $cursor->count();
        $pois = $cursor;

        $poi_additional = array();

        foreach ($pois as $poi) {
            $collection = $db->coupons;

            $coupons = $collection->find(array('coupon_poi_id' => (string) $poi['_id']));

            $cid = Array(); // array of poi id
            foreach ($coupons as $coupon) {
                $cid[] = (string) $coupon['_id'];
            }

            $coupon_count = $collection->count(array('coupon_poi_id' => array('$in' => $cid)));

            $collection = $db->clients;
            if (isset($poi['poi_client_id'])) {
                $client = $collection->findOne(array('_id' => new MongoId((string) $poi['poi_client_id'])));
            }

            if (isset($client)) {
                $client_name = $client['client_name'];
            } else {
                $client_name = "N/A";
            }

            $poi_additional[(string) $poi['_id']] = array("coupon_count" => $coupon_count, "client_name" => $client_name);
        }

        $table_additional = datautil::makePagination($total_documents, $docs_per_page, $page);

        return View::make('admin.poi', array('active_route' => $active_route, 'pois' => $pois,
                    'total_documents' => $total_documents, 'search_text' => $search_text, 'poi_additional' => $poi_additional,
                    'table_additional' => $table_additional));
    }

    public function getAdminUser() {
        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }
        $active_route = "/admin/users";

        $search_text = Request::query('search_text');

        $docs_per_page = 20;
        $page = Request::query('page');

        if (!isset($page)) {
            $page = 1;
        }

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->users;

        $skip = (int) ($docs_per_page * ($page - 1));
        $limit = $docs_per_page;

        if (isset($search_text) && !empty($search_text)) {

            $query = array(
                '$or' => array(
                    array(
                        'email' => array('$regex' => "$search_text")
                    ),
                    array(
                        'first_name' => array('$regex' => "$search_text")
                    ),
                    array(
                        'last_name' => array('$regex' => "$search_text")
                    )
                )
            );

            $cursor = $collection->find($query)->limit($limit)->skip($skip);
        } else {
            $cursor = $collection->find()->limit($limit)->skip($skip);
        }

        $total_documents = $cursor->count();
        $users = $cursor;

        $table_additional = datautil::makePagination($total_documents, $docs_per_page, $page);

        return View::make('admin.users', array('active_route' => $active_route, 'users' => $users,
                    'total_documents' => $total_documents, 'search_text' => $search_text,
                    'table_additional' => $table_additional));
    }

    public function getAdminUserEdit($id) {
        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }

        $active_route = "/admin/users";
        $timezone = datetimeutil::timezone_list();

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->users;

        $user = $collection->findOne(array("_id" => new MongoId($id)));

        return View::make('admin.users_edit', array('active_route' => $active_route, 'user' => $user, 'timezone' => $timezone));
    }

    public function postAdminUserEdit($id) {
        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }

        $email = Input::get('email');

        $first_name = Input::get('first_name');

        $gender = Input::get('gender');

        $last_name = Input::get('last_name');

        $link = Input::get('link');

        $locale = Input::get('locale');

        $name = Input::get('name');

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->users;

        $coll_user = array(
            "email" => $email,
            "first_name" => $first_name,
            "gender" => $gender,
            "last_name" => $last_name,
            "link" => $link,
            "locale" => $locale,
            "name" => $name
        );

        $newdata = array('$set' => $coll_user);
        $collection->update(array("_id" => new MongoId($id)), $newdata);

        return Redirect::to('admin/users/' . $id . '/edit');
    }

    public function postAdminCategoriesAdd() {
        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }

        $cats = Input::get('cats_max');
        $sub = array();
        $main = Input::get("cat_main_0");
        for ($i = 0; $i <= $cats; $i++) {
            $s = Input::get("cat_sub_$i");
            if (isset($s)) {
                $sub[] = $s;
            }
        }

        $img = Input::get('img');

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->categories;

        $coll_categories = array(
            "main" => $main,
            "sub" => $sub,
            "img" => $img,
            "status" => "active"
        );

        $collection->insert($coll_categories);

        return Redirect::to('admin/categories');
    }

    public function postAdminCategoriesEdit($id) {

        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }

        $cats = Input::get('cats_max');
        $sub = array();
        $main = Input::get("cat_main_0");
        for ($i = 0; $i <= $cats; $i++) {
            $s = Input::get("cat_sub_$i");
            if (isset($s)) {
                $sub[] = $s;
            }
        }

        $img = Input::get('img');

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->categories;

        $coll_categories = array(
            "main" => $main,
            "sub" => $sub,
            "img" => $img
        );

        $newdata = array('$set' => $coll_categories);
        $collection->update(array("_id" => new MongoId($id)), $newdata);

        return Redirect::to('admin/categories/' . $id . '/edit?updated=true');
    }

    public function getAdminCoupon() {
        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }
    }

    public function getAdminResource() {
        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }
    }

    public function getAdminCategories() {
        $active_route = '/admin/categories';
        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }

        $search_text = Request::query('search_text');

        $docs_per_page = 20;
        $page = Request::query('page');

        if (!isset($page)) {
            $page = 1;
        }

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->categories;

        $skip = (int) ($docs_per_page * ($page - 1));
        $limit = $docs_per_page;

        if (isset($search_text) && !empty($search_text)) {

            $query = array(
                '$or' => array(
                    array(
                        'name_th' => array('$regex' => "$search_text")
                    ),
                    array(
                        'name_en' => array('$regex' => "$search_text")
                    )
                )
            );

            $cursor = $collection->find($query)->limit($limit)->skip($skip);
        } else {
            $cursor = $collection->find()->limit($limit)->skip($skip);
        }

        $total_documents = $cursor->count();
        $categories = $cursor;

        $table_additional = datautil::makePagination($total_documents, $docs_per_page, $page);

        return View::make('admin.categories', array('active_route' => $active_route, 'categories' => $categories,
                    'total_documents' => $total_documents, 'search_text' => $search_text,
                    'table_additional' => $table_additional));
    }

    public function getAdminCategoriesAdd() {
        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }
        $active_route = "/admin/categories";

        return View::make('admin.categories_add', array('active_route' => $active_route));
    }

    public function getAdminCategoriesEdit($id) {
        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }
        $active_route = "/admin/categories";

        $updated = Request::query('updated');

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->categories;
        $cat = $collection->findOne(array('_id' => new MongoId($id)));

        return View::make('admin.categories_edit', array('active_route' => $active_route, 'category' => $cat, 'updated' => $updated));
    }

    public function getAdminCategoriesDelete($id) {
        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }

        $search_text = Request::query('search_text');

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->categories;
        $poi = $collection->findOne(array('_id' => new MongoId($id)));
        $status = ($poi['status'] == "active" ? "inactive" : "active");
        $del = array(
            "status" => $status
        );

        $newdata = array('$set' => $del);
        $collection->update(array('_id' => new MongoId($id)), $newdata);

        return Redirect::to('admin/categories?search_text=' . $search_text);
    }

    public function getAdminShopItemEdit($id) {
        $active_route = "/admin/poi";

        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }

        $updated = Request::query("updated");

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->categories;

        $sc = $collection->find();
        $shop_cat = $sc;

        $timezone = datetimeutil::timezone_list();

        $collection = $db->poi;
        $query = array('_id' => new MongoId($id));
        $poi = $collection->findOne($query);

        $main_url = "http://" . $_SERVER['HTTP_HOST'] . "/";

        return View::make('admin.poi_edit', array('active_route' => $active_route, 'shop_cat' => $shop_cat, 'main_url' => $main_url, 'timezone' => $timezone, 'poi' => $poi, 'updated' => $updated));
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

            $newfile[] = "$fileName.$extension";

            $dt = new DateTime();
            $now = $dt->format('Y-m-d 00:00:00');

            $server = $_SERVER['HTTP_HOST'];

            if ($client_id != null) {
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
            }


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

    public function postAdminShopItemEdit($id) {

        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }

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

        $collection = $db->poi;
        $query = array('_id' => new MongoId($id));
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

        $files_arr = array();
        if (isset($poi['poi_client_id'])) {
            $collection = $db->clients;
            $query = array('_id' => new MongoId($poi['poi_client_id']));
            $client = $collection->findOne($query);
            $files_arr = self::writeFiles2($db_files, $upload_files, $files_changed, (string) $client['_id']);
        } else {
            $files_arr = self::writeFiles2($db_files, $upload_files, $files_changed, null);
        }

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

        $collection = $db->poi;
        $newdata = array('$set' => $coll_poi);
        $collection->update(array('_id' => new MongoId($id)), $newdata);

        return Redirect::to('admin/poi/' . $id . '/edit');
    }

    public function getAdminDeleteShop($id) {
        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }

        $search_text = Request::query('search_text');

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->poi;
        $poi = $collection->findOne(array('_id' => new MongoId($id)));
        $status = ($poi['status'] == "active" ? "inactive" : "active");
        $shop = array(
            "status" => $status
        );

        $newdata = array('$set' => $shop);
        $collection->update(array('_id' => new MongoId($id)), $newdata);

        return Redirect::to('admin/poi?search_text=' . $search_text);
    }

    public function getAccount() {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }
        $shop_cat = array("restaurant", "hospital", "beauty & salon", "fashion", "gas", "cafe");
        $cemail = Session::get("client_email");

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->clients;
        $query = array('client_email' => $cemail);
        $client = $collection->findOne($query);

        $collection = $db->poi;
        $query = array('poi_client_id' => (string) $client['_id']);
        $pois = $collection->find($query);

        return View::make('clients.account', array('shop_cat' => $shop_cat, 'client' => $client, 'pois' => $pois));
    }

    public function getRegister() {

        $shop_cat = array("restaurant", "hospital", "beauty & salon", "fashion", "gas", "cafe");

        $err = Request::query('err');
        $error = isset($err) ? Lang::get("message.$err") : "";

        return View::make('clients.register', array('shop_cat' => $shop_cat, 'error' => $error)); //->with('error', $error);
    }

    public function getChangePwd() {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }

        $success = Request::query('success');
        $success = isset($success) ? Lang::get("message.$success") : "";

        $error = Request::query('error');
        $error = isset($error) ? Lang::get("message.$error") : "";

        return View::make('clients.changepwd', array('error' => $error, 'success' => $success));
    }

    public function getAdminEditClientAccount($id) {

        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }
        $active_route = "/admin/clients";
        $shop_cat = array("restaurant", "hospital", "beauty & salon", "fashion", "gas", "cafe");

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->clients;
        $cemail = Session::get("client_email");
        $query = array('_id' => new \MongoId($id));

        $item = $collection->findOne($query);
        $client = $item;

        return View::make('admin.clients_edit_account', array('shop_cat' => $shop_cat, 'client' => $client, 'active_route' => $active_route));
    }

    public function postRegister() {

        $cemail = Input::get('client_email');

        $cpassword = md5(Input::get('client_password'));

        $cname = Input::get('client_name');

        $cdesc = Input::get('client_desc');

        $caddr = Input::get('client_addr');

        $ccontact = Input::get('client_contact');

        $ctelephone = Input::get('client_telephone');

        $dt = new DateTime();
        $cjoin_date = $dt->format('Y-m-d H:i:s');

        $cshow_name = Input::get('client_show_name');

        $ccat = Input::get('client_cat');

        $cwebsite = Input::get('client_website');

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->clients;

        $query = array('client_email' => $cemail);
        $item = $collection->findOne($query);

        if (!isset($item['client_email'])) {
            /*
             * doesn't exist, insert new data
             */
            $coll_clients = array(
                "client_email" => $cemail,
                "client_password" => $cpassword,
                "client_name" => $cname,
                "client_desc" => $cdesc,
                "client_addr" => $caddr,
                "client_contact" => $ccontact,
                "client_telephone" => $ctelephone,
                "client_join_date" => $cjoin_date,
                "client_show_name" => $cshow_name,
                "client_cat" => $ccat,
                //"client_poi_id" => $cpoi_id, 
                "client_website" => $cwebsite
            );

            $collection->insert($coll_clients);

            Session::put('client_email', $cemail);

            return Redirect::to('dashboard');
        } else {
            /*
             * already exist, do nothing
             */
            $err = "email_exist";
            return Redirect::to('clients/register?err=' . $err);
        }
    }

    public function postAdminEditClientAccount($id) {

        if (!Session::has("admin")) {
            return Redirect::to('admin');
        }

        $cemail = Input::get('client_email');

        $cname = Input::get('client_name');

        $cdesc = Input::get('client_desc');

        $caddr = Input::get('client_addr');

        $ccontact = Input::get('client_contact');

        $ctelephone = Input::get('client_telephone');

        $cshow_name = Input::get('client_show_name');

        $ccat = Input::get('client_cat');

        $cwebsite = Input::get('client_website');

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->clients;

        $coll_clients = array(
            "client_email" => $cemail,
            //"client_password" => $cpassword,
            "client_name" => $cname,
            "client_desc" => $cdesc,
            "client_addr" => $caddr,
            "client_contact" => $ccontact,
            "client_telephone" => $ctelephone,
            //"client_join_date" => $cjoin_date,
            "client_show_name" => $cshow_name,
            "client_cat" => $ccat,
            //"client_poi_id" => $cpoi_id, 
            "client_website" => $cwebsite
        );

        $newdata = array('$set' => $coll_clients);
        $collection->update(array("_id" => new MongoId($id)), $newdata);

        return Redirect::to('admin/clients');
    }

    public function postChangePwd() {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }

        $client_email = Session::get("client_email");

        $current_password = Input::get('current_password');

        $new_password = Input::get('new_password');

        $confirm_new_password = Input::get('confirm_new_password');

        if (trim($new_password) !== '' && ($new_password == $confirm_new_password)) {

            $new_password = md5($new_password);
            $confirm_new_password = md5($confirm_new_password);

            $connection = new MongoClient("mongodb://localhost");
            $db = $connection->location;
            $collection = $db->clients;

            $newdata = array('$set' => array("client_password" => $confirm_new_password));
            $collection->update(array("client_email" => $client_email), $newdata);

            return Redirect::to('clients/changepwd?success=changepwd_successful');
        } else {
            $error = "passwd_mismatch";
            return Redirect::to('clients/changepwd?error=' . $error);
        }
    }

}
