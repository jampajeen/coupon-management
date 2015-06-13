<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use MongoClient;
use MongoId;

class ResourceController extends Controller {

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

        $collection = $db->resources;
        $query = array('resource_client_id' => (string) $client['_id'], 'status' => 'active');
        $resources = $collection->find($query)->sort(array('resource_date' => -1));

        $main_url = "http://" . $_SERVER['HTTP_HOST'] . "/";

        return View::make('resources.resources', array('client' => $client, 'resources' => $resources, 'main_url' => $main_url));
    }

    public function getRealUrl() {

        $real_url = "";

        return Redirect::to($real_url);
    }

    public function getDeleteResource($id) {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }

        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->resources;

        $query = array(
            "status" => "inactive"
        );

        $newdata = array('$set' => $query);
        $collection->update(array('_id' => new MongoId($id)), $newdata);

        return Redirect::to('resources');
    }

    public function postUpload() {

        if (!Session::has("client_email")) {
            return Redirect::to('clients/login');
        }
        $cemail = Session::get("client_email");


        $connection = new MongoClient("mongodb://localhost");
        $db = $connection->location;

        $collection = $db->clients;
        $query = array('client_email' => $cemail);
        $client = $collection->findOne($query);

        if (Input::hasFile('file') && Input::file('file')->isValid()) {
            $RESOURCES_RELATIVE_PATH = "useruploads";

            $type = Input::get('file_type');

            $path = Input::file('file')->getRealPath();
            $name = Input::file('file')->getClientOriginalName();

            $extension = Input::file('file')->getClientOriginalExtension();

            $size = Input::file('file')->getSize();

            $mime = Input::file('file')->getMimeType();

            $destinationPath = public_path() . "/" . $RESOURCES_RELATIVE_PATH . "/" . $type . "/";
            $fileName = uniqid();
            Input::file('file')->move($destinationPath, $fileName . "." . $extension);

            $dt = new DateTime();
            $now = $dt->format('Y-m-d 00:00:00');

            $server = $_SERVER['HTTP_HOST'];

            $connection = new MongoClient("mongodb://localhost");
            $db = $connection->location;
            $collection = $db->resources;

            $coll_resource = array(
                "resource_client_id" => (string) $client['_id'],
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
        } else {
            // has no files
        }

        return Redirect::to('resources');
    }

}
