<?php

namespace App\Http\Controllers;

use App\Http\Services\Service;
use App\Models\User;
use Artisan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Log;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Parse an xml subscription file
     *
     * @param Request   $request    with xml as body content
     * @return void
     */
    public function fileUpload(Request $request)
    {

        //clearing database FOR TESTING
        Artisan::call('migrate:fresh');

        if ($request->getContent()) {
            $content = $request->getContent();

            $xml       = simplexml_load_string($content);
            $xml_json  = json_encode($xml);
            $xml_array = json_decode($xml_json, true);

            $ss = $xml_array['subscriber'];

            $result = Service::parse($ss);

            return $result;
        }
    }

    /**
     * Returns the user
     *
     * @param integer   $id     the user id
     * @return User     $user   with attached subscriptions
     */
    public function user(int $id):User
    {
        return User::with('subscriptions')->findOrFail($id);
    }

}
