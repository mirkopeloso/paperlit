<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests\FileUploadRequest;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function fileUpload(FileUploadRequest $request){

        if($request->file()) {
            $content = $request->file('file')->get();

            $xml = simplexml_load_string($content);
            $xml_json = json_encode($xml);
            $xml_array = json_decode($xml_json,TRUE);

            foreach($xml_array as $subscriber){
                var_dump($subscriber);
            }







            die();

            return back()
            ->with('success','File has been uploaded.')
            ->with('result', "{empty json}");
        }
   }
}
