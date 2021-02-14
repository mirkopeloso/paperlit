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
     * @param Request $request with xml as body content
     * @return void
     */
    public function fileUpload(Request $request)
    {

        //clearing database FOR TESTING
        Artisan::call('migrate:fresh');

        Log::debug('======================================= UPLOAD STARTED');
        Log::debug('======================================= UPLOAD STARTED');
        Log::debug('======================================= UPLOAD STARTED');
        Log::debug('======================================= UPLOAD STARTED');
        Log::debug('======================================= UPLOAD STARTED');
        Log::debug('======================================= UPLOAD STARTED');
        Log::debug('======================================= UPLOAD STARTED');
        Log::debug('======================================= UPLOAD STARTED');

        if ($request->file()) {
            $content = $request->getContent();

            $xml       = simplexml_load_string($content);
            $xml_json  = json_encode($xml);
            $xml_array = json_decode($xml_json, true);

            $ss = $xml_array['subscriber'];

            $result = Service::parse($ss);

            if ($result['status'] == 'failed') {
                return back()->with('errorUser', json_encode($result['user']))
                    ->withErrors($result['errors']);
            }

            return back()
                ->with('success', 'File has been uploaded.')
                ->with('result', $result['objects']);
        }
    }

    /**
     * Returns the user
     *
     * @param integer $id
     * @return User $user
     */
    public function user(int $id)
    {
        return User::with('subscriptions')->findOrFail($id);
    }

}
