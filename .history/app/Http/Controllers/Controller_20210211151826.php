<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Http\Services\Service;
use App\Models\Subscriber;
use App\Models\Subscription;
use App\Models\User;
use Artisan;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Log;
use Str;
use Hash;
use DB;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function fileUpload(FileUploadRequest $request)
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
            $content = $request->file('file')->get();

            $xml       = simplexml_load_string($content);
            $xml_json  = json_encode($xml);
            $xml_array = json_decode($xml_json, true);

            $ss          = $xml_array['subscriber'];





            $result = Service::parse($ss);

            if($result['status']=='failed')
            return back()->with('errorUser', json_encode($result['user']))
            ->withErrors($result['errors']);



            return back()
                ->with('success', 'File has been uploaded.')
                ->with('result', $subscribers);
        }
    }
}
