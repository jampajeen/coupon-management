<?php

namespace App\Http\Controllers;

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

class ClientController extends Controller {

    public function getForgotPwd() {
        return view('clients.forgotpwd');
    }

    public function postForgotPwd() {

        Log::info("Password reset request");

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->clients;
        $cemail = Session::get("client_email");
        $query = array('client_email' => $cemail);

        $client = $collection->findOne($query);

        if (isset($client)) {
            /*
             * This should be STMP instead of php mail
             */
            $to = $cemail;
            $subject = "Rinxor Service | Reset password request";
            $txt = "You can reset your password by clicking the link below.\r\n http://localhost";
            $headers = "From: support@rinxor.com";

            mail($to, $subject, $txt, $headers);

            Log::info("New password sent to client");
        } else {
            Log::info("Password not Match");
        }

        return view('clients.forgotpwd');
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

    public function getLogin() {
        $err = Request::query('err');
        $error = isset($err) ? Lang::get("message.$err") : "";

        return View::make('clients.login', array('error' => $error)); //->with('error', $error);
    }

    public function getRegister() {

        $shop_cat = array("restaurant", "hospital", "beauty & salon", "fashion", "gas", "cafe");

        $err = Request::query('err');
        $error = isset($err) ? Lang::get("message.$err") : "";

        return View::make('clients.register', array('shop_cat' => $shop_cat, 'error' => $error)); //->with('error', $error);
    }

    public function getLogout() {

        if (Session::has("client_email")) {
            Session::forget("client_email");
        }

        return Redirect::to('clients/login');
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

    public function getEditAccount() {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }
        $shop_cat = array("restaurant", "hospital", "beauty & salon", "fashion", "gas", "cafe");

        $updated = Request::query('updated');

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->clients;
        $cemail = Session::get("client_email");
        $query = array('client_email' => $cemail);

        $item = $collection->findOne($query);
        $client = $item;

        return View::make('clients.editaccount', array('shop_cat' => $shop_cat, 'client' => $client, 'updated' => $updated));
    }

    public function postLogin() {

        $cemail = Input::get('client_email');
        $cpassword = Input::get('client_password');

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;
        $collection = $db->clients;
        $query = array('client_email' => $cemail, 'client_password' => md5($cpassword));

        $item = $collection->findOne($query);

        if (isset($item['client_email'])) {

            Session::put('client_email', $cemail);
            return Redirect::to('dashboard');
        } else {

            $err = "login_error_user_passwd";
            return Redirect::to('clients/login?err=' . $err);
        }
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
                "client_website" => $cwebsite,
                "status" => "active"
            );

            $collection->insert($coll_clients);

            Session::put('client_email', $cemail);

            return Redirect::to('dashboard');
        } else {
            $err = "email_exist";
            return Redirect::to('clients/register?err=' . $err);
        }
    }

    public function postEditAccount() {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }
        $current_email = Session::get("client_email");

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
            "client_name" => $cname,
            "client_desc" => $cdesc,
            "client_addr" => $caddr,
            "client_contact" => $ccontact,
            "client_telephone" => $ctelephone,
            "client_show_name" => $cshow_name,
            "client_cat" => $ccat,
            "client_website" => $cwebsite
        );

        $newdata = array('$set' => $coll_clients);
        $collection->update(array("client_email" => $current_email), $newdata);

        return Redirect::to('clients/editaccount?updated=true');
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
